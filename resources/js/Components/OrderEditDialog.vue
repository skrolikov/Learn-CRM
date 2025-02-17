<template>
  <Sidebar header="Редактировать заказ" v-model:visible="localVisible" position="right" style="width: 1000px">
    <div class="slider-wrapper" style="display: flex; height: 100%;">

      <!-- Левая часть: Форма редактирования -->
      <div class="left-panel custom-scroll-hover pr-2" style="width: 50%; overflow-y: auto;">
        <div class="p-fluid">
          <!-- Секция: Статус -->
          <div class="p-col-12 p-md-5 mb-3">
            <IftaLabel>
              <Select id="subStatus" :options="subStatuses" v-model="orderForm.sub_status_id" optionLabel="name"
                optionValue="id" placeholder="Выберите статус" fluid />
              <label for="subStatus" class="mb-2">Статус</label>
            </IftaLabel>
          </div>

          <!-- Секция: Клиент -->
          <div class="p-col-12">
            <Panel header="Клиент" class="mb-3">
              <SelectButton v-model="clientMode" :options="clientOptions" optionLabel="name" optionValue="value" />

              <!-- Если выбран существующий клиент -->
              <div v-if="clientMode === 'existing'" class="p-field mt-3">
                <IftaLabel>
                  <Select id="clientDropdown" :options="formattedClients" v-model="selectedClientId"
                    optionLabel="formattedName" optionValue="id" placeholder="Выберите клиента" fluid />
                  <label for="clientDropdown" class="mb-2">Клиент</label>
                </IftaLabel>
                <!-- Отображение номера телефона выбранного клиента -->
                <div class="p-field mt-2">
                  <IftaLabel>
                    <InputText id="clientPhone" size="small" v-model="selectedClientPhone" disabled fluid />
                    <label for="clientPhone" class="mb-2">Номер телефона</label>
                  </IftaLabel>
                </div>
              </div>

              <!-- Если создаётся новый клиент -->
              <div v-else class="mt-3">
                <div class="p-grid">
                  <div class="p-col-12 p-md-6">
                    <IftaLabel>
                      <label>Имя</label>
                      <InputText v-model="newClient.name" placeholder="Имя" fluid />
                    </IftaLabel>
                  </div>
                  <div class="p-col-12 p-md-6 mt-2">
                    <IftaLabel>
                      <label>Фамилия</label>
                      <InputText v-model="newClient.first_name" placeholder="Фамилия" fluid />
                    </IftaLabel>
                  </div>
                  <div class="p-col-12 p-md-6 mt-2">
                    <IftaLabel>
                      <label>Отчество</label>
                      <InputText v-model="newClient.middle_name" placeholder="Отчество" fluid />
                    </IftaLabel>
                  </div>
                </div>
                <div class="p-col-12 p-md-6 mt-2">
                  <IftaLabel>
                    <label>Телефон</label>
                    <InputText v-model="newClient.phone" placeholder="Телефон" fluid />
                  </IftaLabel>
                </div>
              </div>
            </Panel>
          </div>

          <Divider />

          <!-- Секция: Устройство -->
          <div class="p-col-12 p-md-7">
            <Panel header="Устройство" class="mb-3">
              <div class="p-grid">
                <div class="p-col-12 p-md-6">
                  <IftaLabel>
                    <Select id="deviceType" :options="deviceTypes" v-model="deviceData.device_type_id"
                      optionLabel="name" optionValue="id" placeholder="Выберите тип" fluid />
                    <label for="deviceType" class="mb-2">Тип устройства</label>
                  </IftaLabel>
                </div>
                <div class="p-col-12 p-md-6 mt-2">
                  <IftaLabel>
                    <Select id="deviceModel" :options="deviceModels" v-model="deviceData.device_model_id"
                      optionLabel="name" optionValue="id" placeholder="Выберите модель" fluid />
                    <label for="deviceModel" class="mb-2">Модель устройства</label>
                  </IftaLabel>
                </div>
                <div class="p-col-12 p-md-6 mt-2">
                  <IftaLabel>
                    <Select id="deviceBrand" :options="deviceBrands" v-model="deviceData.device_brand_id"
                      optionLabel="name" optionValue="id" placeholder="Выберите бренд" fluid />
                    <label for="deviceBrand" class="mb-2">Бренд устройства</label>
                  </IftaLabel>
                </div>
                <div class="p-col-12 p-md-6 mt-2">
                  <IftaLabel>
                    <Select id="deviceColor" :options="deviceColors" v-model="deviceData.device_color_id"
                      optionLabel="name" optionValue="id" placeholder="Выберите цвет" fluid />
                    <label for="deviceColor" class="mb-2">Цвет устройства</label>
                  </IftaLabel>
                </div>
                <div class="p-col-12 p-md-6 mt-2">
                  <IftaLabel>
                    <InputText id="imei" v-model="deviceData.imei" placeholder="Введите IMEI" fluid />
                    <label for="imei" class="mb-2">IMEI</label>
                  </IftaLabel>
                </div>
              </div>
            </Panel>
          </div>

          <Divider />

          <!-- Итоговые суммы -->
          <div class="p-col-12 p-md-12" style="text-align: right;">
            <h4>Сумма услуг: {{ totalServicesCost }}</h4>
            <h4>Сумма запчастей: {{ totalProductsCost }}</h4>
            <h4>Итого: {{ totalServicesCost + totalProductsCost }}</h4>
            <h4>Прибыль составляет: {{ profit }}</h4>
          </div>

          <!-- Секция: Услуги -->
          <div class="p-col-12 p-md-6">
            <Panel header="Услуги" class="mb-3">
              <MultiSelect v-model="selectedServices" :options="servicesList" optionLabel="name" optionValue="id"
                placeholder="Выберите услуги" fluid />
            </Panel>
          </div>

          <!-- Секция: Запчасти / Товары -->
          <div class="p-col-12 p-md-6">
            <Panel header="Запчасти / Товары" class="mb-3">
              <MultiSelect v-model="selectedProducts" :options="productsList" optionLabel="sku" optionValue="id"
                placeholder="Выберите запчасти" fluid />
            </Panel>
          </div>

          <!-- Кнопки: Отмена и Сохранить -->
          <div class="p-text-right mt-3">
            <Button label="Отмена" icon="pi pi-times" class="p-button-text" @click="onCancel" />
            <Button label="Сохранить" icon="pi pi-check" @click="onUpdate" />
          </div>
        </div>
      </div>

      <!-- Правая часть: История в виде чата -->
      <div class="right-panel pl-2"
        style="width: 50%; border-left: 1px solid #ccc; display: flex; flex-direction: column;">
        <Panel header="История" style="flex: 1;">
          <div class="chat-area" style="height: 100%; width: 100%; overflow-y: auto;">
            <!-- Здесь можно отрисовывать сообщения чата -->
            <p>История чата будет отображаться здесь во всю ширину.</p>
          </div>
        </Panel>
      </div>

    </div>
  </Sidebar>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';

// Импорт компонентов PrimeVue
import Sidebar from 'primevue/sidebar';
import InputText from 'primevue/inputtext';
import RadioButton from 'primevue/radiobutton';
import MultiSelect from 'primevue/multiselect';
import Button from 'primevue/button';
import { Divider, IftaLabel, Panel, Select, SelectButton } from 'primevue';

// Свойства компонента
const props = defineProps({
  visible: { type: Boolean, default: false },
  orderData: { type: Object, default: null }
});
const emit = defineEmits(['update:visible', 'orderUpdated']);

// Локальное управление видимостью
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

// Reactive объект для редактирования заказа (пока что только sub_status)
const orderForm = ref({
  sub_status_id: null
});

// --- Клиент ---
const clientMode = ref('existing');

const clientOptions = ref([
  { name: 'Использовать существующего', value: 'existing' },
  { name: 'Создать нового', value: 'new' },
]);
const clients = ref([]);
const selectedClientId = ref(null);
const newClient = ref({
  name: '',
  first_name: '',
  middle_name: '',
  phone: ''
});

// Вычисляемое свойство для форматированного отображения Фамилия Имя Отчество
const formattedClients = computed(() =>
  clients.value.map(client => ({
    ...client,
    formattedName: `${client.name} ${client.first_name} ${client.middle_name}`,
  }))
);

// Вычисляемое свойство для получения номера телефона выбранного клиента
const selectedClientPhone = computed(() => {
  const client = clients.value.find(client => client.id === selectedClientId.value);
  return client ? client.phone : '';
});

// Устройство
const deviceTypes = ref([]);
const deviceModels = ref([]);
const deviceBrands = ref([]);
const deviceColors = ref([]);
const deviceData = ref({
  id: null,
  device_type_id: null,
  device_model_id: null,
  device_brand_id: null,
  device_color_id: null,
  imei: ''
});

// Подстатусы
const subStatuses = ref([]);

// Услуги и запчасти
const servicesList = ref([]);
const productsList = ref([]);
const selectedServices = ref([]);
const selectedProducts = ref([]);

// Вычисляемые свойства для услуг и запчастей
const totalServicesCost = computed(() => {
  return selectedServices.value.reduce((acc, serviceId) => {
    const service = servicesList.value.find(s => s.id === serviceId);
    return acc + (service ? Number(service.price) : 0);
  }, 0);
});

const totalProductsCost = computed(() => {
  return selectedProducts.value.reduce((acc, productId) => {
    const product = productsList.value.find(p => p.id === productId);
    return acc + (product ? Number(product.retail_price) : 0);
  }, 0);
});

// Новое вычисляемое свойство для расчета прибыли
const profit = computed(() => {
  const totalPurchasePrice = selectedProducts.value.reduce((acc, productId) => {
    const product = productsList.value.find(p => p.id === productId);
    return acc + (product ? Number(product.purchase_price) : 0);
  }, 0);
  return totalServicesCost.value + (totalProductsCost.value - totalPurchasePrice);
});

// Функции загрузки данных справочников
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

const fetchSubStatuses = async () => {
  try {
    const response = await axios.get('/api/sub-statuses');
    subStatuses.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке подстатусов', error);
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

onMounted(() => {
  fetchClients();
  fetchDeviceTypes();
  fetchDeviceModels();
  fetchDeviceBrands();
  fetchDeviceColors();
  fetchSubStatuses();
  fetchServices();
  fetchProducts();
});

// Инициализация полей формы на основе orderData
watch(
  () => props.orderData,
  (newOrder) => {
    if (newOrder) {
      // Инициализация клиента
      selectedClientId.value = newOrder.client?.id || null;
      clientMode.value = 'existing'; // редактируем существующего клиента

      // Инициализация устройства
      if (newOrder.device) {
        deviceData.value = {
          id: newOrder.device.id,
          device_type_id: newOrder.device.device_type_id,
          device_model_id: newOrder.device.device_model_id,
          device_brand_id: newOrder.device.device_brand_id,
          device_color_id: newOrder.device.device_color_id,
          imei: newOrder.device.imei
        };
      }

      // Инициализация подстатуса
      orderForm.value.sub_status_id = newOrder.subStatus?.id || null;

      // Инициализация услуг и запчастей (предполагается, что orderData.services и orderData.products — массивы объектов)
      selectedServices.value = newOrder.services ? newOrder.services.map(s => s.id) : [];
      selectedProducts.value = newOrder.products ? newOrder.products.map(p => p.id) : [];
    }
  },
  { immediate: true }
);

// Методы для кнопок
const onCancel = () => {
  emit('update:visible', false);
};

const onUpdate = async () => {
  try {
    // Обработка клиента
    let clientId = null;
    if (clientMode.value === 'new') {
      const clientResponse = await axios.post('/api/clients', newClient.value);
      clientId = clientResponse.data.id;
    } else {
      clientId = selectedClientId.value;
    }

    // Обновление устройства
    if (deviceData.value.id) {
      await axios.put(`/api/devices/${deviceData.value.id}`, deviceData.value);
    } else {
      const deviceResponse = await axios.post('/api/devices', deviceData.value);
      deviceData.value.id = deviceResponse.data.id;
    }

    // Формирование полезной нагрузки для обновления заказа
    const orderPayload = {
      client_id: clientId,
      device_id: deviceData.value.id,
      sub_status_id: orderForm.value.sub_status_id,
      services: selectedServices.value,
      products: selectedProducts.value.map(id => ({ id, quantity: 1 }))
    };

    // Обновление заказа через API
    await axios.put(`/api/orders/${props.orderData.id}`, orderPayload);
    emit('orderUpdated', { ...props.orderData, ...orderPayload });
    emit('update:visible', false);
  } catch (error) {
    console.error('Ошибка при обновлении заказа', error);
  }
};
</script>
