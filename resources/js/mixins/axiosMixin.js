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
				this.referralReasons = data.data
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


		getCasee(caseeId){
			this.$Progress.start();
			axios.get("/api/casees/"+caseeId)
            .then(({data}) => {
                this.casee = data.data
            });
			this.$Progress.finish();
        },



		getCaseeHousingReferrals(caseeId){
			this.$Progress.start();
			axios.get('/api/casees/'+ caseeId +'/housing-referrals', { params: { casee_id: caseeId } } )
			.then((response) => {
				// success
                this.caseeHousingReferrals = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

		getCaseeReferrals(caseeId){
			this.$Progress.start();
			axios.get('/api/casees/'+ caseeId +'/referrals', { params: { casee_id: caseeId } } )
			.then((response) => {
				// success
                this.caseeReferrals = response.data.data;
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

		getHousingGrantStatuses(){
			this.$Progress.start();
			axios.get('/api/housing-grant-statuses/')
			.then((response) => {
				// success
				this.housingGrantStatuses = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

		getBeneficiaryStatuses(){
			this.$Progress.start();
			axios.get('/api/beneficiary-statuses/')
			.then((response) => {
				// success
				this.beneficiaryStatuses = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

		getRelationships(){
			this.$Progress.start();
			axios.get('/api/relationships/')
			.then((response) => {
				// success
				this.relationships = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},
		getMonths(){
			this.$Progress.start();
			axios.get('/api/months/')
			.then((response) => {
				// success
				this.months = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

		getCaseeBeneficiaries(caseeId){
			this.$Progress.start();
			axios.get('/api/casees/'+ caseeId +'/beneficiaries', { params: { casee_id: caseeId } })
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

		getUsers(){
			this.$Progress.start();
			axios.get('/api/users/')
			.then((response) => {
				this.users = response.data.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e);
			})
		},

		getServiceTypes(){
			this.$Progress.start();
			axios.get('/api/service-types/')
			.then((response) => {
				this.serviceTypes = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e);
			})
		},
	},

	
};


