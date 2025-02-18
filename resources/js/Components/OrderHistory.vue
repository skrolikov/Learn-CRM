<template>
  <Panel header="История" style="flex: 1;">
    <div class="chat-area" style="height: 100%; width: 100%; overflow-y: auto;">
      <div v-for="item in history" :key="item.id" class="chat-item" style="margin-bottom: 10px;">
        <div v-if="item.user">
          <p>Пользователь: {{ item.user.name }}</p>
        </div>
        <div v-else>
          <p>Неизвестный пользователь</p>
        </div>

        <p>{{ formatComment(item) }}</p>

        <span style="font-size: 0.8em; color: #888;">{{ formatDate(item.created_at) }}</span>
      </div>
    </div>

    <div class="comment-input" style="margin-top: 10px; display: flex;">
      <InputText v-model="newComment" placeholder="Ваш комментарий" style="flex: 1; margin-right: 5px;" />
      <Button label="Отправить" icon="pi pi-send" @click="sendComment" />
    </div>
  </Panel>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Panel from 'primevue/panel';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const props = defineProps({
  orderId: {
    type: Number,
    required: true,
  },
});

const history = ref([]);
const newComment = ref('');

// Соответствия статусов (подставь свои)
const statusLabels = {
  1: "Новый",
  2: "На ремонте",
  3: "На согласовании",
  4: "Готов",
  5: "Выдан",
  6: "Выдан без ремонта",
  7: "Клиент отказался",
};

const fetchHistory = async () => {
  try {
    const response = await axios.get(`/api/order-history/${props.orderId}`);
    history.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки истории', error);
  }
};

const sendComment = async () => {
  if (!newComment.value.trim()) return;
  try {
    const response = await axios.post(`/api/order-history`, {
      order_id: props.orderId,
      comment: newComment.value,
    });
    history.value.unshift(response.data);
    newComment.value = '';
  } catch (error) {
    console.error('Ошибка отправки комментария', error);
  }
};

// Форматирование комментария
const formatComment = (item) => {
  if (item.comment) {
    return `Комментарий: ${item.comment}`;
  }
  
  if (item.field === "sub_status_id" && item.old_value && item.new_value) {
    const oldStatus = statusLabels[item.old_value] || `#${item.old_value}`;
    const newStatus = statusLabels[item.new_value] || `#${item.new_value}`;
    return `Статус изменен с "${oldStatus}" на "${newStatus}"`;
  }

  return "Без комментария";
};

// Форматирование даты
const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleString();
};

onMounted(() => {
  fetchHistory();
});
</script>


<style scoped>
.history-panel {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.chat-area {
  flex: 1;
  width: 100%;
  overflow-y: auto;
  padding: 10px;
}

.chat-item {
  margin-bottom: 20px;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

.chat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}

.user-info {
  display: flex;
  align-items: center;
  font-weight: bold;
}

.user-info i {
  margin-right: 5px;
}

.date-info {
  font-size: 0.8em;
  color: #888;
}

.chat-comment {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.comment-input {
  display: flex;
  margin: 10px;
}

.comment-field {
  flex: 1;
  margin-right: 5px;
}

.send-button {
  white-space: nowrap;
}

.loading-state,
.no-history {
  text-align: center;
  padding: 20px;
  color: #888;
}
</style>
