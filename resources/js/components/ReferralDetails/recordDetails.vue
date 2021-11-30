<template>
    <section>
        <h1>Records</h1>
        <h2>{{ record.month.name }}</h2>
        <div>

        </div>
    </section>
</template>
<script>
export default {
    props: {
        referralId: {
            type: String,
            required: true
        },
        recordId: {
            type: String,
            required: true
        },
    }
}
</script>