export default {
	// data(){
    //     return{
    //         abilities: ['user_create'],
    //     }
    // },
	methods: {
		async getAll(method, url, dataObj){
			try {
                return await axios({
                    method: method,
                    url: url,
                    data:dataObj,
                });
            } catch (e) {
                return e.response
            }	  
		},


		getNationalities(){
			this.$Progress.start();
			axios.get("/api/nationalities")
			.then(({data}) => {
				this.nationalities = data.data
			});
			this.$Progress.finish();
		},

		getReferralSources(){			
			this.$Progress.start();
			axios.get("/api/referral_sources").then(({data}) => (this.referralSources = data.data));
			this.$Progress.finish();
		},
		getReferralReasons(){
			this.$Progress.start();
			axios.get("/api/referral_reasons")
			.then(({data}) => {
				this.referral_reasons = data.data
			});
			this.$Progress.finish();
		},
		getRoles(){			
			this.$Progress.start();
			axios.get("/api/roles")
			.then(({data}) => (this.roles = data.data));
			this.$Progress.finish();
		},


		getPermissions(){			
			this.$Progress.start();
			axios.get("/api/permission")
			.then(({data}) => (this.permissions = data.data));
			this.$Progress.finish();
		},
		// getAbilities(){			
		// 	this.$Progress.start();
		// 	axios.get("/api/abilities")
		// 	.then(({data}) => (this.abilities = data.data));
		// 	this.$Progress.finish();
		// },
		getServices(){			
			this.$Progress.start();
			axios.get("/api/services").then(({data}) => (this.services = data.data));
			this.$Progress.finish();
		},

		getCasee(caseeId){
			this.$Progress.start();
			axios.get("/api/casees/"+caseeId)
            .then(({data}) => {
                this.casee = data.data
            });
			this.$Progress.finish();
        },

		getCaseebeneficiaries(caseeId){
			this.$Progress.start();
			axios.get('/api/casees/'+ caseeId +'/beneficiaries')
			.then((response) => {
				// success
                this.caseeBeneficiaries = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

		getReferral(referralId){			
			this.$Progress.start();
			axios.get("/api/referrals/"+referralId)
            .then(({data}) => (this.referral = data.data));
			this.$Progress.finish();
		},
	},


	
};


