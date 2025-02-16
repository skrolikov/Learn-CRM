<template>
  <Dialog :header="dialogHeader" v-model:visible="localVisible" :modal="true" closable style="width: 650px" class="!bg-gray-50">
    <form @submit.prevent="onSave" novalidate>
      <div class="p-fluid p-grid">
        <!-- Клиент -->
        <div class="p-col-12 p-md-6">
          <div class="bg-white p-4 mb-2 rounded-md border">
            <h3 class="section-header">Клиент</h3>
            <div class="p-field-radiobutton">
              <RadioButton inputId="existingClient" v-model="clientMode" value="existing" />
              <label for="existingClient" class="p-radio-label">Выбрать существующего</label>
            </div>
            <div class="p-field-radiobutton">
              <RadioButton inputId="newClient" v-model="clientMode" value="new" />
              <label for="newClient" class="p-radio-label">Создать нового</label>
            </div>
            <div v-if="clientMode === 'existing'" class="p-field">
              <label for="clientDropdown">Существующий клиент</label>
              <Select id="clientDropdown" :options="clients" v-model="selectedClientId" optionLabel="name"
                optionValue="id" placeholder="Выберите клиента" />
            </div>
            <div v-else class="p-field">
              <label>Имя</label>
              <InputText v-model="newClient.name" placeholder="Имя" />
              <label>Фамилия</label>
              <InputText v-model="newClient.first_name" placeholder="Фамилия" />
              <label>Отчество</label>
              <InputText v-model="newClient.middle_name" placeholder="Отчество" />
              <label>Телефон</label>
              <InputText v-model="newClient.phone" placeholder="Телефон" />
            </div>
          </div>
        </div>

        <!-- Устройство -->
        <div class="p-col-12 p-md-6">
          <div class="bg-white p-4 mb-2 rounded-md border">
            <h3 class="section-header">Устройство</h3>
            <div class="p-field">
              <label for="deviceType">Тип устройства</label>
              <Select id="deviceType" :options="deviceTypes" v-model="deviceData.device_type_id" optionLabel="name"
                optionValue="id" placeholder="Выберите тип" />
            </div>
            <div class="p-field">
              <label for="deviceModel">Модель устройства</label>
              <Select id="deviceModel" :options="deviceModels" v-model="deviceData.device_model_id" optionLabel="name"
                optionValue="id" placeholder="Выберите модель" />
            </div>
            <div class="p-field">
              <label for="deviceBrand">Бренд устройства</label>
              <Select id="deviceBrand" :options="deviceBrands" v-model="deviceData.device_brand_id" optionLabel="name"
                optionValue="id" placeholder="Выберите бренд" />
            </div>
            <div class="p-field">
              <label for="deviceColor">Цвет устройства</label>
              <Select id="deviceColor" :options="deviceColors" v-model="deviceData.device_color_id" optionLabel="name"
                optionValue="id" placeholder="Выберите цвет" />
            </div>
            <div class="p-field">
              <label for="imei">IMEI</label>
              <InputText id="imei" v-model="deviceData.imei" placeholder="Введите IMEI" />
            </div>
            <div class="p-field">
              <label for="subStatus">Статус заказа</label>
              <Select id="subStatus" :options="subStatuses" v-model="orderForm.sub_status_id" optionLabel="name"
                optionValue="id" placeholder="Выберите статус" />
            </div>
          </div>
        </div>

        <!-- Услуги -->
        <div class="p-col-12">
          <div class="bg-white p-4 mb-2 rounded-md border">
            <h3 class="section-header">Услуги</h3>
            <div class="p-field">
              <MultiSelect v-model="selectedServices" :options="servicesList" optionLabel="name" optionValue="id"
                placeholder="Выберите услуги" display="chip" />
            </div>
          </div>
        </div>

        <!-- Запчасти / Товары -->
        <div class="p-col-12">
          <div class="bg-white p-4 mb-2 rounded-md border">
            <h3 class="section-header">Запчасти / Товары</h3>
            <div class="p-field">
              <MultiSelect v-model="selectedProducts" :options="productsList" optionLabel="sku" optionValue="id"
                placeholder="Выберите запчасти" display="chip" />
            </div>
          </div>
        </div>
      </div>

      <!-- Футер диалога -->
      <div class="dialog-footer p-d-flex p-jc-end">
        <Button label="Отмена" icon="pi pi-times" class="p-button-text" @click="onCancel" />
        <Button label="Сохранить" icon="pi pi-check" type="submit" :disabled="!isFormValid" />
      </div>
    </form>
  </Dialog>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import RadioButton from 'primevue/radiobutton';
import MultiSelect from 'primevue/multiselect';
import Button from 'primevue/button';
import { Select } from 'primevue';

const props = defineProps({
  visible: { type: Boolean, default: false },
  orderData: { type: Object, default: null }
});
const emit = defineEmits(['update:visible', 'orderSaved']);

// Локальное управление видимостью диалога
const localVisible = ref(props.visible);
watch(
  () => props.visible,
  (newVal) => {
    localVisible.value = newVal;
  }
);
watch(localVisible, (newVal) => {
  emit('update:visible', newVal);
});
const dialogHeader = computed(() => (props.orderData ? 'Редактировать заказ' : 'Новый заказ'));

// Данные для формы
const orderForm = ref({
  sub_status_id: null
});

const clientMode = ref('existing'); // 'existing' или 'new'
const clients = ref([]);
const selectedClientId = ref(null);
const newClient = ref({
  name: '',
  first_name: '',
  middle_name: '',
  phone: ''
});

const deviceTypes = ref([]);
const deviceModels = ref([]);
const deviceBrands = ref([]);
const deviceColors = ref([]);
const deviceData = ref({
  device_type_id: null,
  device_model_id: null,
  device_brand_id: null,
  device_color_id: null,
  imei: ''
});

const servicesList = ref([]);
const productsList = ref([]);
const selectedServices = ref([]);
const selectedProducts = ref([]);
const subStatuses = ref([]);

// Валидация формы
const isFormValid = computed(() => {
  // Валидация клиента
  if (clientMode.value === 'existing') {
    if (!selectedClientId.value) return false;
  } else {
    if (!newClient.value.name || !newClient.value.first_name || !newClient.value.phone) return false;
  }
  // Валидация устройства
  if (
    !deviceData.value.device_type_id ||
    !deviceData.value.device_model_id ||
    !deviceData.value.device_brand_id ||
    !deviceData.value.device_color_id ||
    !deviceData.value.imei
  ) {
    return false;
  }
  // Валидация статуса заказа
  if (!orderForm.value.sub_status_id) return false;

  return true;
});

// --- Функции загрузки данных ---
const fetchClients = async () => {
  try {
    const response = await axios.get('/api/clients');
    clients.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке клиентов', error);
  }
};

const fetchDeviceTypes = async () => {
  try {
    const response = await axios.get('/api/device-types');
    deviceTypes.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке типов устройств', error);
  }
};

const fetchDeviceModels = async () => {
  try {
    const response = await axios.get('/api/device-models');
    deviceModels.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке моделей устройств', error);
  }
};

const fetchDeviceBrands = async () => {
  try {
    const response = await axios.get('/api/device-brands');
    deviceBrands.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке брендов устройств', error);
  }
};

const fetchDeviceColors = async () => {
  try {
    const response = await axios.get('/api/device-colors');
    deviceColors.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке цветов устройств', error);
  }
};

const fetchServices = async () => {
  try {
    const response = await axios.get('/api/services');
    servicesList.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке услуг', error);
  }
};

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    productsList.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке запчастей', error);
  }
};

const fetchSubStatuses = async () => {
  try {
    const response = await axios.get('/api/sub-statuses');
    subStatuses.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке подстатусов', error);
  }
};

onMounted(() => {
  fetchClients();
  fetchDeviceTypes();
  fetchDeviceModels();
  fetchDeviceBrands();
  fetchDeviceColors();
  fetchServices();
  fetchProducts();
  fetchSubStatuses();
});

// Методы диалога
const onCancel = () => {
  emit('update:visible', false);
};

const onSave = async () => {
  try {
    // Создание/выбор клиента
    let clientId = null;
    if (clientMode.value === 'new') {
      const clientResponse = await axios.post('/api/clients', newClient.value);
      clientId = clientResponse.data.id;
    } else {
      clientId = selectedClientId.value;
    }

    // Создание устройства
    const deviceResponse = await axios.post('/api/devices', deviceData.value);
    const deviceId = deviceResponse.data.id;

    // Формирование полезной нагрузки для создания заказа
    const orderPayload = {
      client_id: clientId,
      device_id: deviceId,
      sub_status_id: orderForm.value.sub_status_id,
      services: selectedServices.value, // массив id услуг
      products: selectedProducts.value.map(id => ({ id, quantity: 1 }))
    };

    const orderResponse = await axios.post('/api/orders', orderPayload);
    emit('orderSaved', orderResponse.data);
    emit('update:visible', false);
  } catch (error) {
    console.error('Ошибка при сохранении заказа', error);
  }
};
</script>

<style scoped>

.section-header {
  margin-bottom: 0.75rem;
  color: #3f51b5;
  border-bottom: 1px solid #eee;
  padding-bottom: 0.5rem;
}

.p-field-radiobutton {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
}

.p-radio-label {
  margin-left: 0.5rem;
}

.dialog-footer {
  margin-top: 1rem;
}
</style>
