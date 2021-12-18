<template>
<div class="wrapper">
    <!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->


		<SideBar></SideBar>


		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

			<!-- Main content -->
			<div class="content">
				<div class="container-fluid" >
					
					<router-view :key="$route.path"></router-view>
					
					<!-- set progressbar -->
					<vue-progress-bar></vue-progress-bar>
				</div>
			</div>
		</div>

		<!-- <Footer></Footer> -->

</div>
</template>

<script>
import SideBar from "./layouts/SideBar.vue"
import Footer from "./layouts/Footer.vue"
export default {
	components: {
		SideBar,
		Footer,
	},

    async created(){
		if(localStorage.hasOwnProperty("blog_token")){
			// attach token to all axios calls by default
			axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("blog_token");
			
			// redirect to login if token doesn't match
			// axios.interceptors.response.use(response => {
			// 	return response;
			// }, error => {
			// 	if (error.response.status === 401) {
			// 		window.location.replace("/login");
			// 	}
			// 	return error;
			// });

			await this.$store.dispatch('currentUser/getAbilities')
			await this.$store.dispatch('currentUser/getUser');

			// console.log('abilities ')
			// console.log(this.$store.state.currentUser.abilities)
			// console.log('user ')
			// console.log(this.$store.state.currentUser.user)

		}else{
			window.location.replace("/login");
		}

    },

}
</script>