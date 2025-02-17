<template>
    <AppLayout title="Заявки">
        <div class="card pl-4">
            <!-- Сводные карточки -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-4">
                <!-- Всего заказов -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-blue-300 border border !border-blue-200">
                        <template #title>
                            <i class="pi pi-shopping-cart bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Всего заказов</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">Общее число</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">{{ totalOrders }}</h1>
                        </template>
                    </Card>
                </div>

                <!-- Отказов -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-red-300 border !border-red-200">
                        <template #title>
                            <i class="pi pi-ban bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Отказов</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">Клиент отказался</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">{{ countRefused }}</h1>
                        </template>
                    </Card>
                </div>

                <!-- Готовы -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-green-300 border !border-green-200">
                        <template #title>
                            <i class="pi pi-check-circle bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Готовы</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">Статус "Готов"</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">{{ countReady }}</h1>
                        </template>
                    </Card>
                </div>

                <!-- Уникальных клиентов -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-purple-300 border !border-purple-200">
                        <template #title>
                            <i class="pi pi-users bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Уникальных клиентов</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">По телефону</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">{{ uniqueClients }}</h1>
                        </template>
                    </Card>
                </div>
            </div>

            <!-- Разделитель -->
            <div class="my-4">
                <Divider />
            </div>

            <!-- Карточки прибыли -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                <!-- Прибыль сегодня -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-orange-300 border !border-orange-200">
                        <template #title>
                            <i class="pi pi-dollar bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Прибыль сегодня</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">За текущий день</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">
                                {{ profitToday.toFixed(2) }} &#8381;
                            </h1>
                            <Chart type="line" :data="profitTodayChartData" :options="lineChartOptions"
                                style="width: 360px" />
                        </template>
                    </Card>
                </div>

                <!-- Прибыль за неделю -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-teal-300 border !border-teal-200">
                        <template #title>
                            <i class="pi pi-chart-line bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Прибыль за неделю</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">Последние 7 дней</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">
                                {{ profitWeek.toFixed(2) }} &#8381;
                            </h1>
                            <!-- Пример мини-графика Line для "недели" -->
                            <Chart type="line" :data="profitWeekChartData" :options="lineChartOptions"
                                style="width: 360px" />
                        </template>
                    </Card>
                </div>

                <!-- Прибыль за месяц -->
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl !bg-yellow-300 border !border-yellow-200">
                        <template #title>
                            <i class="pi pi-calendar bg-gray-800/25 text-white p-3 rounded-full" />
                            <p>Прибыль за месяц</p>
                        </template>
                        <template #subtitle>
                            <p class="text-black/50">Текущий месяц</p>
                        </template>
                        <template #content>
                            <h1 class="text-3xl font-bold text-black/25">
                                {{ profitMonth.toFixed(2) }} &#8381;
                            </h1>
                            <Chart type="line" :data="profitMonthChartData" :options="lineChartOptions"
                                style="width: 360px" />
                        </template>
                    </Card>
                </div>
            </div>

            <!-- Разделитель -->
            <div class="my-4">
                <Divider />
            </div>

            <!-- Диаграмма и таблица последних заказов -->
            <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
                <div>
                    <Card class="!shadow-none text-center !rounded-2xl border">
                        <template #title>Распределение заказов по под-статусам</template>
                        <template #content>
                            <Chart type="pie" :data="chartData" :options="chartOptions" style="max-height: 400px;" />
                        </template>
                    </Card>
                </div>

                <div>
                    <Card class="!shadow-none text-center !rounded-2xl border">
                        <template #title>Последние заказы</template>
                        <template #content>
                            <DataTable :value="orders" responsiveLayout="scroll" class="custom-table">
                                <Column field="id" header="ID" sortable />
                                <Column field="clientName" header="Клиент" sortable />
                                <Column field="brandModel" header="Устройство" sortable />
                                <Column field="subStatusName" header="Статус" sortable />
                            </DataTable>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Chart from 'primevue/chart';
import Divider from 'primevue/divider';
import AppLayout from '@/Layouts/AppLayout.vue';

const orders = ref([]);

// Функция для расчёта прибыли по одному заказу
const calculateProfit = (order) => {
    let profit = 0;
    const raw = order.raw;
    if (raw.products && Array.isArray(raw.products)) {
        raw.products.forEach(product => {
            const retail = parseFloat(product.retail_price) || 0;
            const purchase = parseFloat(product.purchase_price) || 0;
            const quantity = product.pivot?.quantity || 1;
            profit += (retail - purchase) * quantity;
        });
    }
    if (raw.services && Array.isArray(raw.services)) {
        raw.services.forEach(service => {
            const price = parseFloat(service.price) || 0;
            profit += price;
        });
    }
    return profit;
};

onMounted(() => {
    axios.get('/api/orders')
        .then(response => {
            const apiData = response.data;
            orders.value = apiData.map(item => ({
                id: item.id,
                clientName: item.client.name,
                brandModel: `${item.device.brand.name} ${item.device.model.name}`,
                subStatusName: item.sub_status.name,
                created_at: item.created_at,
                raw: item
            }));
        })
        .catch(error => {
            console.error('Ошибка при загрузке данных:', error);
        });
});

// Метрики
const totalOrders = computed(() => orders.value.length);

const countRefused = computed(() =>
    orders.value.filter(o => o.subStatusName === 'Клиент отказался').length
);

const countReady = computed(() =>
    orders.value.filter(o => o.subStatusName === 'Готов').length
);

const uniqueClients = computed(() => {
    const phones = orders.value.map(o => o.raw?.client?.phone);
    return new Set(phones).size;
});

// Прибыль (общие значения)
const profitToday = computed(() => {
    const today = new Date();
    return orders.value.reduce((sum, order) => {
        const orderDate = new Date(order.created_at);
        if (orderDate.toDateString() === today.toDateString()) {
            return sum + calculateProfit(order);
        }
        return sum;
    }, 0);
});

const profitWeek = computed(() => {
    const today = new Date();
    return orders.value.reduce((sum, order) => {
        const orderDate = new Date(order.created_at);
        const diffDays = (today - orderDate) / (1000 * 3600 * 24);
        if (diffDays < 7) {
            return sum + calculateProfit(order);
        }
        return sum;
    }, 0);
});

const profitMonth = computed(() => {
    const today = new Date();
    return orders.value.reduce((sum, order) => {
        const orderDate = new Date(order.created_at);
        if (
            orderDate.getMonth() === today.getMonth() &&
            orderDate.getFullYear() === today.getFullYear()
        ) {
            return sum + calculateProfit(order);
        }
        return sum;
    }, 0);
});

//Pie Chart (распределение заказов по статусам)
const chartData = computed(() => {
    const counts = {};
    orders.value.forEach(item => {
        const status = item.subStatusName || 'Неизвестно';
        counts[status] = (counts[status] || 0) + 1;
    });
    return {
        labels: Object.keys(counts),
        datasets: [
            {
                data: Object.values(counts),
                backgroundColor: [
                    '#42A5F5',
                    '#66BB6A',
                    '#FFA726',
                    '#EC407A',
                    '#AB47BC'
                ]
            }
        ]
    };
});

// Опции для всех графиков
const chartOptions = {
    plugins: {
        legend: {
            display: true,
            position: 'bottom'
        }
    }
};

// Линейные графики для прибыли

// (1) "Сегодня": разбивка прибыли за сегодня по 6 интервалам времени (часы)
const profitTodayChartData = computed(() => {
    const intervals = [0, 4, 8, 12, 16, 20, 24];
    const dataset = [0, 0, 0, 0, 0, 0];

    const today = new Date();
    orders.value.forEach(order => {
        const orderDate = new Date(order.created_at);
        if (orderDate.toDateString() === today.toDateString()) {
            const hour = orderDate.getHours();
            const index = intervals.findIndex(val => hour < val) - 1;
            if (index >= 0 && index < dataset.length) {
                dataset[index] += calculateProfit(order);
            }
        }
    });

    return {
        labels: ['0-4ч', '4-8ч', '8-12ч', '12-16ч', '16-20ч', '20-24ч'],
        datasets: [
            {
                label: 'Прибыль (сегодня)',
                data: dataset,
                fill: false,
                borderColor: '#935421'
            }
        ]
    };
});

// (2) "Неделя": прибыль за 7 дней
const profitWeekChartData = computed(() => {
    const result = [];
    for (let i = 6; i >= 0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        result.push({
            date: d.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit' }),
            profit: 0,
            dayKey: d.toDateString(),
        });
    }

    orders.value.forEach(order => {
        const orderDate = new Date(order.created_at).toDateString();
        const item = result.find(r => r.dayKey === orderDate);
        if (item) {
            item.profit += calculateProfit(order);
        }
    });

    return {
        labels: result.map(r => r.date),
        datasets: [
            {
                label: 'Прибыль (7 дней)',
                data: result.map(r => r.profit),
                fill: false,
                borderColor: '#1aaa98'
            }
        ]
    };
});

// (3) "Месяц": прибыль по дням текущего месяца
const profitMonthChartData = computed(() => {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    // Количество дней в текущем месяце
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const result = [];
    for (let d = 1; d <= daysInMonth; d++) {
        result.push({
            day: d,
            profit: 0
        });
    }

    orders.value.forEach(order => {
        const orderDate = new Date(order.created_at);
        if (
            orderDate.getMonth() === month &&
            orderDate.getFullYear() === year
        ) {
            const dayNum = orderDate.getDate();
            const item = result.find(r => r.day === dayNum);
            if (item) {
                item.profit += calculateProfit(order);
            }
        }
    });

    return {
        labels: result.map(r => String(r.day)),
        datasets: [
            {
                label: 'Прибыль (текущий месяц)',
                data: result.map(r => r.profit),
                fill: false,
                borderColor: '#b28c18'
            }
        ]
    };
});

// Общие настройки для Line-графиков
const lineChartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};
</script>