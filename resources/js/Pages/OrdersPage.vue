<template>
  <AppLayout title="Заявки">
    <div class="card pl-4">
      <!-- Панель управления -->
      <Toolbar class="mb-6 !rounded-2xl !bg-white/50">
        <template #start>
          <IconField>
            <InputIcon><i class="pi pi-search" /></InputIcon>
            <InputText v-model="globalFilter" class="!rounded-xl !bg-white/50" placeholder="Поиск..." />
          </IconField>
        </template>

        <template #end>
          <div class="flex flex-wrap">
            <Button icon="pi pi-refresh" class="p-button-outlined !rounded-xl mr-2" @click="fetchOrders" />
            <Button label="Новый" icon="pi pi-plus" class="mt-2 !rounded-xl md:mt-0" @click="openNewOrderDialog" />
          </div>
        </template>
      </Toolbar>
      <DataTable class="!bg-white/50 rounded-2xl" ref="dt" selectionMode="single" responsiveLayout="scroll"
        v-model:sortField="sortField" v-model:sortOrder="sortOrder" :value="orders" dataKey="id" :filters="filters"
        :loading="loading" :globalFilterFields="['client.name', 'device.imei', 'subStatus.name']" @filter="onFilter"
        @row-click="onRowSelect" loadingIcon="pi pi-spin pi-spinner" paginator :rows="10"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[10, 20, 50]"
        currentPageReportTemplate="Отображается {first} до {last} из {totalRecords} заявок">

        <Column field="id" header="ID" style="width: 5%" :sortable="true" />
        <Column header="Клиент" sortable>
          <template #body="order">
            <div>
              <div class="font-medium">{{ order.data.client.name }}</div>
              <div class="text-sm text-gray-500">{{ order.data.client.phone }}</div>
            </div>
          </template>
        </Column>
        <Column header="Устройство" sortable>
          <template #body="order">
            <div>
              <div class="text-sm text-gray-500">{{ order.data.device.brand.name }}</div>
              <div class="font-medium">{{ order.data.device.model.name }}</div>
            </div>
          </template>
        </Column>
        <Column field="sub_status.name" style="width: 20%" header="Статус" sortable>
          <template #body="order">
            <Tag :value="order.data.sub_status.name" :severity="getStatusColor(order.data.sub_status.name)" />
          </template>
        </Column>
        <Column header="Сумма">
          <template #body="order">
            {{ getOrderTotal(order.data) }} ₽
          </template>
        </Column>
        <Column header="Действия" style="width: 10%">
          <template #body="{ rowData }">
            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger p-mr-2" @click="deleteOrder(rowData.id)"
              title="Удалить" />
          </template>
        </Column>
        <!-- Если данных нет, показываем сообщение -->
        <template #empty>
          <tr>
            <td colspan="5" class="p-text-center">
              Нет данных для отображения
            </td>
          </tr>
        </template>
      </DataTable>

      <!-- Компонент создания заказа -->
      <OrderDialog v-model:visible="createDialog" @orderSaved="onOrderSaved" />

      <!-- Компонент редактирования заказа -->
      <OrderEditDialog v-model:visible="editDialog" :orderData="selectedOrder" @orderUpdated="onOrderUpdated" />

      <!-- Компонент уведомлений -->
      <Toast />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// PrimeVue компоненты
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';

// Собственные компоненты
import AppLayout from '@/Layouts/AppLayout.vue';
import OrderDialog from '@/Components/OrderDialog.vue';
import OrderEditDialog from '@/Components/OrderEditDialog.vue';

// PrimeVue сервисы (не забудьте зарегистрировать их в main.js)
// import ToastService from 'primevue/toastservice';
// import FilterService from 'primevue/api'; // если хотите использовать расширенные фильтры

const getStatusColor = (status) => {
  switch (status) {
    case 'На согласовании':
      return 'warning';
    case 'На ремонте':
      return 'info';
    case 'Готов':
      return 'success';
    case 'Выдан':
      return 'success';
    case 'Клиент отказался':
      return 'danger';
    default:
      return 'secondary';
  }
};

// Локальные переменные и состояния
const orders = ref([]);
const createDialog = ref(false);
const editDialog = ref(false);
const selectedOrder = ref(null);
const loading = ref(false);

// Управление сортировкой
const sortField = ref(null);
const sortOrder = ref(null);

// Управление фильтрами
const globalFilter = ref('');
const filters = ref({
  global: { value: null, matchMode: 'contains' },
});

const fetchOrders = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/orders');
    orders.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке заказов:', error);
    showError('Не удалось загрузить заказы');
  } finally {
    loading.value = false;
  }
  console.log(orders)
};

/**
 * Считает сумму по услугам (price) и товарам (retail_price * quantity).
 * @param {Object} order Объект "заявки", тот что приходит в order.data
 * @returns {Number} Суммарная стоимость
 */
const getOrderTotal = (order) => {
  let total = 0;

  // Сумма по товарам (products)
  if (order.products && Array.isArray(order.products)) {
    for (const product of order.products) {
      const price = parseFloat(product.retail_price) || 0;
      const quantity = product?.pivot?.quantity ? parseInt(product.pivot.quantity, 10) : 1;
      total += price * quantity;
    }
  }

  // Сумма по услугам (services)
  if (order.services && Array.isArray(order.services)) {
    for (const service of order.services) {
      total += parseFloat(service.price) || 0;
    }
  }

  return total;
};

onMounted(() => {
  fetchOrders();
});

const openNewOrderDialog = () => {
  createDialog.value = true;
};

// При клике по строке открываем диалог редактирования
const onRowSelect = (event) => {
  selectedOrder.value = event.data;
  editDialog.value = true;
};

// Удаление заказа
const deleteOrder = async (id) => {
  if (confirm('Вы действительно хотите удалить заказ?')) {
    loading.value = true;
    try {
      await axios.delete(`/api/orders/${id}`);
      showSuccess('Заказ успешно удалён');
      fetchOrders();
    } catch (error) {
      console.error('Ошибка при удалении заказа:', error);
      showError('Не удалось удалить заказ');
    } finally {
      loading.value = false;
    }
  }
};

// Когда заказ создан
const onOrderSaved = () => {
  showSuccess('Заказ успешно создан');
  fetchOrders();
};

// Когда заказ отредактирован
const onOrderUpdated = () => {
  showSuccess('Заказ успешно обновлён');
  fetchOrders();
};

// Обновление глобального фильтра
const onFilter = () => {
  filters.value.global.value = globalFilter.value;
};

// Методы уведомлений
import { useToast } from 'primevue/usetoast';
import { IconField, InputIcon, Select, Tag, Toolbar } from 'primevue';
const toast = useToast();

const showSuccess = (message) => {
  toast.add({ severity: 'success', summary: 'Успешно', detail: message, life: 3000 });
};

const showError = (message) => {
  toast.add({ severity: 'error', summary: 'Ошибка', detail: message, life: 3000 });
};
</script>