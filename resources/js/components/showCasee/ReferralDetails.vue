<template>
    <div>
        <section>
            <h1>Referrals</h1>
            <h2>{{ referral.id }}</h2>
            <div class="experience-details">
                <p>{{ referral.id }}</p>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    props: {
        referralId: {
            type: String,
            required: true
        }
    }
}
</script>