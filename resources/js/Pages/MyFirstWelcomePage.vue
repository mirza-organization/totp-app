<script setup>
/* Node Packages */
// import Layout from './Layout'
import { ref, reactive } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { router, useForm } from '@inertiajs/vue3'
/* Vue Components */
import ViewBtn from '../Components/ViewBtn.vue';
import CompanyLogo from '../Components/CompanyLogo.vue';
/* Properties */
// const { info, pagination } = defineProps({
//     info: Object,
//     pagination: Object,
// });
// console.log(info.links);
// console.log("Pagination: ");
// console.log(pagination);

const form = useForm({
    uuid: null,
    account_name: null,
    secret: null,
    issuer: null
});
const info = ref({});
const showListView = ref(true);
const showGridView = ref(false);
const userProvidedUrl = ref('');
const userSelectedId = ref(null);
const userEnteredAcccountName = ref(null);

const addAccountDialog = ref(false);
const editAccountDialog = ref(false);
const deleteAccountDialog = ref(false);
/* Convert them to a reactive object for easier manipulation */
const dialogs = reactive({
    addAccountDialog,
    editAccountDialog,
    deleteAccountDialog,
});

/* Methods */
// const generateUrl = (routeName) => route(routeName);

// import { usePage } from '@inertiajs/inertia';
// import { debounce } from 'lodash/debounce';

const searchTerm = ref('');
const searchResults = ref([]);
const results = ref([]);
const showSearchResults = ref(false);

// const search = debounce(async () => {
//   const { data } = await axios.get(route('search.account'), {
//     params: { search: search.value },
//   });
//   results.value = data.results;
//   console.log(results.value)
// }, 500); // Debounce after 500ms

// const initialData = usePage().props;

const searchThis = () => {
    try {
        if (searchTerm.value === '') {
            showSearchResults.value = false;
            toggleView('list');
        } else {
            axios.get('/search-account', {
                params: {
                    account_name: searchTerm.value
                }
            })
                .then(function (response) {
                    searchResults.value = response.data.data.data;
                    showSearchResults.value = true;

                    console.log('i am a searched result: ');
                    console.log(searchResults);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                    console.log('i am always executed');
                    // info.value = response.data.data;
                });
        }
    } catch (error) {
        console.error('LogError:', error);
    }
}

const editAccount = () => {
    try {
        form.uuid = userSelectedId.value;
        form.account_name = userEnteredAcccountName.value;
        form.put('/edit-account', {
            preserveScroll: true,
            onSuccess: () => form.reset()
        })
        toggleDialog('editAccountDialog', false);
    } catch (error) {
        console.log('LogError:', error);
    }
}

const deleteAccount = () => {
    try {
        form.uuid = userSelectedId.value;
        form.delete('/delete-account', {
            preserveScroll: true,
            onSuccess: () => form.reset()
        })
        toggleDialog('deleteAccountDialog', false);
    } catch (error) {
        console.log('LogError:', error);
    }
}

const addAccount = () => {
    try {
        const urlToJsonObj = urlToJson(userProvidedUrl.value);
        form.account_name = urlToJsonObj.accountName;
        form.secret = urlToJsonObj.secret;
        form.issuer = urlToJsonObj.issuer;
        form.post('/add-account', {
            preserveScroll: true,
            onSuccess: () => form.reset(),
            // onError: alert('Server side error in submitting the form')
        })
        toggleDialog('addAccountDialog', false);
    } catch (error) {
        console.log('LogError:', error);
    }
}

const urlToJson = (url) => {
    try {
        /* Parse the URL */
        const parsedUrl = new URL(url);
        return {
            accountName: parsedUrl.pathname.split(':')[1],
            secret: parsedUrl.searchParams.get('secret'),
            issuer: parsedUrl.searchParams.get('issuer')
        };
    } catch (error) {
        console.error('LogError:', error);
    }
}

const toggleView = (viewName) => {
    try {
        if (viewName === 'list') {
            showListView.value = true;
            showGridView.value = false;
        } else {
            showListView.value = false;
            showGridView.value = true;
        }
    } catch (error) {
        console.error('LogError:', error);
    }
}

const toggleDialog = (givenDialog, givenStatus, givenId = null) => {
    try {
        dialogs[givenDialog] = givenStatus;
        userSelectedId.value = givenId;
    } catch (error) {
        console.error('LogError:', error);
    }
}

const getResults = async (page = 1) => {
    // console.log(route('home.get.info'));
    const response = await fetch(`http://127.0.0.1:8000/get-info?page=${page}`);
    info.value = await response.json();
}

getResults();
</script>

<template>

    <!-- Add Account Dialog -->
    <TransitionRoot as="template" :show="dialogs['addAccountDialog']">
        <Dialog as="div" class="relative z-10" @close="toggleDialog('addAccountDialog', false)">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <form @submit.prevent="addAccount">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <font-awesome-icon icon="fa-solid fa-user" />
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                            <DialogTitle as="h3"
                                                class="text-base font-semibold leading-6 text-gray-900">
                                                Add Another Account
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    You can add Authenticator accounts such as Gmail, Facebook, Dropbox
                                                    and
                                                    many more using our TOTP Web App.
                                                </p>
                                            </div>
                                            <div class="mt-2">
                                                <input type="text" v-model="userProvidedUrl"
                                                    class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                                                    placeholder="Enter the URL provided after scanning QR code..." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit" :disabled="form.processing"
                                        class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-900 sm:ml-3 sm:w-auto">
                                        Add Account
                                    </button>
                                    <button type="button" @click="toggleDialog('addAccountDialog', false)"
                                        ref="cancelButtonRef"
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </DialogPanel>
                        </form>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!-- Edit Dialog -->
    <TransitionRoot as="template" :show="dialogs['editAccountDialog']">
        <Dialog as="div" class="relative z-10" @close="toggleDialog('editAccountDialog', false)">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <form @submit.prevent="editAccount">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <font-awesome-icon icon="fa-regular fa-pen-to-square" />
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-64 sm:w-72">
                                            <DialogTitle as="h3"
                                                class="text-base font-semibold leading-6 text-gray-900">
                                                Edit Account
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <input type="text" v-model="userEnteredAcccountName"
                                                    class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                                                    placeholder="Enter new account name..." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit" :disabled="form.processing"
                                        class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-900 sm:ml-3 sm:w-auto">
                                        Update
                                    </button>
                                    <button type="button" @click="toggleDialog('editAccountDialog', false)"
                                        ref="cancelButtonRef"
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </DialogPanel>
                        </form>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!-- Delete Dialog -->
    <TransitionRoot as="template" :show="dialogs['deleteAccountDialog']">
        <Dialog as="div" class="relative z-10" @close="toggleDialog('deleteAccountDialog', false)">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <form @submit.prevent="deleteAccount">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                            <DialogTitle as="h3"
                                                class="text-base font-semibold leading-6 text-gray-900">
                                                Delete Account
                                            </DialogTitle>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Are you sure you want to delete this account??<br>
                                                    It cannot be receovered.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit" :disabled="form.processing"
                                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                                        Confirm
                                    </button>
                                    <button type="button" @click="toggleDialog('deleteAccountDialog', false)"
                                        ref="cancelButtonRef"
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </DialogPanel>
                        </form>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <div class="flex flex-col items-center justify-center sm:py-20 m-5 sm:m-0">
        <div>
            <CompanyLogo />
        </div>
        <div class="w-full md:w-4/6 lg:w-3/6 xl:w-2/6 bg-white shadow hover:shadow-2xl rounded-lg p-2 sm:p-4">
            <div class="flex items-center mb-4">
                <!-- Search Field -->
                <input type="text" placeholder="Search..."
                    class="flex-grow w-2 pl-4 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    v-model="searchTerm" @keyup="searchThis" />
                <!-- Show Grid Button -->
                <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg" v-if="showListView"
                    @click="toggleView('grid')" title="Show grid view">
                    <font-awesome-icon icon="fa-solid fa-grip-vertical" />
                </button>
                <!-- Show List Button -->
                <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg" v-if="showGridView"
                    @click="toggleView('list')" title="Show list view">
                    <font-awesome-icon icon="fa-solid fa-list" />
                </button>
                <!-- Add New Account Button -->
                <button class="ml-2 px-4 py-2 bg-green-500 text-white rounded-lg"
                    @click="toggleDialog('addAccountDialog', true)">
                    <font-awesome-icon icon="fa-solid fa-plus" />
                </button>
            </div>

            <!-- Search Results -->
            <!-- showSearchResults -->
            <div class="overflow-x-auto" v-if="showSearchResults">
                <table class="min-w-full divide-y divide-gray-200">
                    <caption class="caption-top font-light text-gray-500 text-sm p-5 bg-slate-100 rounded-t-lg">
                        All Rights Reserved © 2024 Grace Technologies
                    </caption>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="singleIndex in searchResults" :key="singleIndex.id">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 text-balance md:text-nowrap">
                                {{ singleIndex.issuer }}:
                                {{ singleIndex.account_name.length <= 20 ? singleIndex.account_name :
                                    singleIndex.account_name.substring(0, 7) + '...' }} </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <!-- <ViewBtn :uuid="singleIndex.id"/> -->
                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                    <Link :href="'/2FA/' + singleIndex.id"
                                        class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white">
                                    View
                                    </Link>
                                    <button
                                        class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                        @click="toggleDialog('editAccountDialog', true, singleIndex.id)">
                                        Edit
                                    </button>
                                    <button
                                        class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                        @click="toggleDialog('deleteAccountDialog', true, singleIndex.id)">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <!-- List View -->
                <div class="overflow-x-auto" v-if="showListView">
                    <table class="min-w-full divide-y divide-gray-200">
                        <caption class="caption-top font-light text-gray-500 text-sm p-5 bg-slate-100 rounded-t-lg">
                            All Rights Reserved © 2024 Grace Technologies
                        </caption>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="singleIndex in info.data" :key="singleIndex.id">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-balance md:text-nowrap">
                                    {{ singleIndex.issuer }}:
                                    {{ singleIndex.account_name.length <= 20 ? singleIndex.account_name :
                                        singleIndex.account_name.substring(0, 7) + '...' }} </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <!-- <ViewBtn :uuid="singleIndex.id"/> -->
                                    <div class="inline-flex rounded-md shadow-sm" role="group">
                                        <Link :href="'/2FA/' + singleIndex.id"
                                            class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white">
                                        View
                                        </Link>
                                        <button
                                            class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                            @click="toggleDialog('editAccountDialog', true, singleIndex.id)">
                                            Edit
                                        </button>
                                        <button
                                            class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                            @click="toggleDialog('deleteAccountDialog', true, singleIndex.id)">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center pt-3">
                                    <TailwindPagination :data="info" @pagination-change-page="getResults" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Grid View -->
                <div v-if="showGridView">
                    <div
                        class="caption-top font-light text-center text-gray-500 text-sm p-5 mb-1 bg-slate-100 rounded-t-lg w-full">
                        All Rights Reserved © 2024 Grace Technologies
                    </div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-1 gap-x-1 items-start">
                        <li class="relative flex flex-col items-start pl-4 sm:pl-0 xl:pl-7 pr-7 py-6 rounded border border-slate-200"
                            v-for="singleIndex in info.data">
                            <div class="order-1 sm:ml-6 xl:ml-0">
                                <h3 class="mb-1 text-slate-900 font-semibold">
                                    {{ singleIndex.issuer }}:
                                    {{ singleIndex.account_name.length <= 20 ? singleIndex.account_name :
                                        singleIndex.account_name.substring(0, 7) + '...' }} </h3>
                                        <div
                                            class="group inline-flex items-center h-9 text-sm font-semibold whitespace-nowrap focus:outline-none focus:ring-2 mt-6">
                                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                                <Link :href="'/2FA/' + singleIndex.id"
                                                    class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white">
                                                View
                                                </Link>
                                                <button
                                                    class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                                    @click="toggleDialog('editAccountDialog', true, singleIndex.id)">
                                                    Edit
                                                </button>
                                                <button
                                                    class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-orange-500 focus:z-10 focus:ring-2 focus:ring-orange-500 focus:text-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-orange-500 dark:focus:text-white"
                                                    @click="toggleDialog('deleteAccountDialog', true, singleIndex.id)">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>