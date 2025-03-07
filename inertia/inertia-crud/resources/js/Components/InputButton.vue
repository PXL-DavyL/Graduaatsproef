<template>
    <button
        class="inline-flex items-center rounded-md border border-transparent  px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2  focus:ring-offset-2 "
        :class="{
            'bg-sky-400 hover:bg-sky-300 focus:bg-sky-600 focus:ring-sky-500 active:bg-sky-500': props.type === 'primary',
            'bg-slate-400 hover:bg-slate-300 focus:bg-slate-600 focus:ring-slate-500 active:bg-slate-500': props.type === 'secondary',
            'bg-rose-500 hover:bg-rose-400 focus:bg-rose-600 focus:ring-rose-500 active:bg-rose-600': props.type === 'danger'
        }"
        >
        <slot />
        </button>
    </template>

    <script setup>

        const props = defineProps({
        type: {
            type: String,
            default: 'primary',
            validator: (value) => ['primary', 'secondary', 'danger'].includes(value)
        }
        })

    </script>
