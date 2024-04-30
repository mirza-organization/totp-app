<script setup>
/* Node Packages */
import { ref, onMounted } from 'vue';
import { TOTP } from 'totp-generator';
/* Vue Components */
import CompanyLogo from '../Components/CompanyLogo.vue';
/* Properties */
const { info } = defineProps({
   info: Object
});
console.log(info)
const seconds = ref(30);
const alertStatus = ref('Copied!');
const alertMsg = ref('2FA code has been copied to your clipboard');
const showAlert = ref(false);
const generated2FACode = ref(null);
/* Methods */
const showAlertFunction = () => showAlert.value = true;

const hideAlertFunction = () => {
    try {
        setTimeout(() => {
            showAlert.value = false;
        }, 3000);
    } catch (error) {
        console.error('LogError:', error);
    }
};

const generate2FA = () => {
    try {
        const { otp, expires } = TOTP.generate(info.secret)
        generated2FACode.value = otp;
        return {
            otp: otp,
            expiry: new Date(expires).toLocaleTimeString(),
            accountName: info.account_name,
            secret: info.secret,
            issuer: info.issuer
        };
    } catch (error) {
        console.error('LogError:', error);
    }
};

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(generated2FACode.value);
        showAlertFunction();
        hideAlertFunction();
    } catch (error) {
        console.error('LogError:', error);
    }
};

const updateTimer = () => {
    try {
        setInterval(() => {
            seconds.value--;
            /* Reset time after 30 seconds */
            if (seconds.value === 0) {
                generate2FA();
                seconds.value = 30;
            }
        }, 1000);
    } catch (error) {
        console.error('LogError:', error);
    }
};

/* Start the timer */
onMounted(updateTimer);
generate2FA();
</script>
<template>
    <div class="flex flex-col items-center justify-center py-52 m-2 sm:m-0">
        <div class="w-full md:w-4/6 lg:w-3/6 xl:w-2/6 bg-white shadow hover:shadow-2xl rounded-lg p-2 sm:p-4">
            <CompanyLogo />
            <!-- Issuer -->
            <h2 class="text-4xl font-bold text-center mb-2 text-black">{{ info.issuer }}</h2>

            <!-- Title -->
            <h2 class="text-xl font-bold text-center mb-2 text-slate-400 hover:text-black">{{ info.account_name }}</h2>

            <!-- 2FA code -->
            <p class="text-center mb-10 text-7xl tracking-widest font-serif">{{ generated2FACode }}</p>

            <!-- Timer and copy to clipboard icon -->
            <div class="flex justify-center items-center">
                <div class="mx-3 mb-3 text-xs sm:text-base">
                    This OTP will change in<span class="text-3xl mx-1"> {{ seconds }}</span>seconds
                </div>
                <div class="mr-2">
                    <button @click="copyToClipboard" class="p-2 rounded-full w-12 h-12 text-2xl bg-black hover:bg-orange-500 text-white" title="Copy to clipboard">
                        <font-awesome-icon icon="fa-regular fa-copy" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Alert -->
    <div class="text-center py-4 lg:px-4" v-if="showAlert">
        <div class="m-3 p-2 bg-black/50 items-center text-white leading-none rounded-full inline-flex" role="alert">
            <span class="flex rounded-full bg-orange-500  px-2 py-1 text-xs font-light sm:font-bold mr-3">{{ alertStatus }}</span>
            <span class="font-light sm:font-semibold mr-2 text-left flex-auto">
                {{ alertMsg }}
            </span>
        </div>
    </div>
</template>