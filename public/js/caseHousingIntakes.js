"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[178],{2990:(e,r,a)=>{a.d(r,{Z:()=>i});var s=a(7897),t=a.n(s),n=a(1519),o=a.n(n)()(t());o.push([e.id,".clickable[data-v-571d6b26]{cursor:pointer}","",{version:3,sources:["webpack://./resources/js/components/CaseDetails/CaseHousingIntakes/CaseHousingIntakes.vue"],names:[],mappings:"AACA,4BACA,cACA",sourcesContent:['<style scoped>\n.clickable {\n    cursor: pointer\n}\n</style>\n<template>\n\n    <div>\n        <div class="card-body">\n            <div class="row ml-2">\n\t\t\t\t<button class="btn btn-success btn-sm mr-2" @click="showCreateHousingIntakeModal">\n\t\t\t\t\x3c!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can(\'role_create\')"> --\x3e\n\t\t\t\t\t<i class="fas fa-plus-circle"></i><span><b> Referral</b></span>\n\t\t\t\t</button>\n\t\t\t\t<button class="btn btn-secondary btn-sm" @click="getCaseeHousingReferrals(caseeId)">\n\t\t\t\t\t<i class="fas fa-sync-alt"></i>\n\t\t\t\t</button>\n\t\t\t</div>\n            <div class="row mt-3">\n                <p v-show="!caseeHousingReferrals.length" class="font-italic ml-5">This case has no referrals!</p>\n\n                <table v-show="caseeHousingReferrals.length" class="table table-hover table-sm">\n                    <thead>\n                        <tr>\n                            <th>Source</th>\n                            <th>Referral Date</th>\n                            <th>Grant Status</th>\n                            <th>Grant Amount</th>\n                            <th>Assigned Housing Advocate</th>\n                            <th>Actions</th>\n                        </tr>\n                    </thead>\n                    <tbody v-if="this.caseeHousingReferrals">\n                        <tr v-for="referral in this.caseeHousingReferrals" :key="referral.id">\n                            <td>{{ referral.referral_source.name  }}</td>\n                            <td>{{ referral.referral_date | myDateShort }}</td>\n                            <td>\n                                <span v-show="referral.grant_status.name == \'Accepted\'" class="badge badge-pill badge-success">{{ referral.grant_status.name }}</span>\n                                <span v-show="referral.grant_status.name == \'Rejected\'" class="badge badge-pill badge-danger">{{ referral.grant_status.name }}</span>\n                                <span v-show="referral.grant_status.name == \'Pending\'" class="badge badge-pill badge-warning">{{ referral.grant_status.name }}</span>\n                            </td>\n                            <td>{{ referral.grant_amount }}</td>\n                            <td>{{ referral.assigned_housing_advocate.name }}</td>\n\n                            <td>\n                                \x3c!-- <router-link\n                                :to="{name: \'referralDetails\', params: { caseeId: caseeId, referralId: referral.id }}"\n                                class="fa fa-eye blue align-middle mr-2">\n                                </router-link> --\x3e\n                                <a class="clickable" @click="showEditHousingIntakeModal(referral)">\n                                    <i class="fas fa-pencil-alt blue mr-2"></i>\n                                </a>\n                                \n                                <a class="clickable">\n                                    <i class="fa fa-trash red"></i>\n                                </a>\n\n                            </td>\n                        </tr>\n                    </tbody>\n                </table>\n            </div>\n\n        </div>\n\n\t\t<HousingIntakeModal v-if="casee" :caseeId="casee.id"\n\t\t:v-if="selectedHousingReferral.id"\n\t\t:housingReferralEditMode=\'housingReferralEditMode\' \n\t\t:selectedHousingReferral=\'selectedHousingReferral\' \n\t\tv-on:caseeHousingReferralsChanged="getCasee(caseeId)">\n\t\t</HousingIntakeModal>\n\n    </div>\n</template>\n\n\n\n<script>\nimport Form from \'vform\'\nimport HousingIntakeModal from \'./HousingIntakeModal\'\nimport Multiselect from \'vue-multiselect\'\nimport router from \'../../../router\'\nimport axiosMixin from \'../../../mixins/axiosMixin\'\n\nexport default {\n    name: \'caseHousingReferrals\',\n    components: {\n        Multiselect,\n        HousingIntakeModal,\n        \n    },\n    mixins: [axiosMixin],\n    props: {\n        caseeId: {\n            // type: Number, \n            required: true\n        }\n    },\n    data(){\n        return {\n            casee: \'\',\n            caseeHousingReferrals: [],\n            housingReferralEditMode: false,\n\t\t\tselectedHousingReferral: {},\n        }\n    },\n    methods: {\n        \n        showCreateHousingIntakeModal(){\n\t\t\tthis.housingReferralEditMode = false;\n\t\t\tthis.selectedHousingReferral = {};\n\t\t\t$(\'#housingIntakeModal\').modal(\'show\')\n\t\t},\n\n        showEditHousingIntakeModal(referral){\n\t\t\tthis.housingReferralEditMode = true;\n\t\t\tthis.selectedHousingReferral = referral;\n\t\t\t$(\'#housingIntakeModal\').modal(\'show\')\n\t\t},\n\n    },\n    created() {\n        this.getCasee(this.caseeId);\n        this.getCaseeHousingReferrals(this.caseeId);\n\n        Fire.$on(\'caseeHousingReferralsChanged\', () => {\n            this.getCaseeHousingReferrals(this.caseeId);\n\t\t});\n    }\n}\n<\/script>'],sourceRoot:""}]);const i=o},3176:(e,r,a)=>{a.r(r),a.d(r,{default:()=>_});var s=a(5714),t=a(7907),n=a.n(t),o=a(5480);const i={mixins:[o.Z],components:{Multiselect:n()},props:{housingReferralEditMode:Boolean,selectedHousingReferral:Object,caseeId:{required:!0}},data:function(){return{casee:"",referralSources:[],housingGrantStatuses:[],housingReferralForm:new s.ZP({id:"",casee_id:"",referral_source_id:"",referral_date:"",referring_person_name:"",referring_person_email:"",referral_narrative_reason:"",grant_status_id:"",grant_amount:""})}},watch:{selectedHousingReferral:function(e,r){this.housingReferralForm.fill(this.selectedHousingReferral),this.housingReferralForm.casee_id=this.$route.params.caseeId}},methods:{createHousingReferral:function(){var e=this;this.$Progress.start(),this.housingReferralForm.post("/api/housing-referrals").then((function(r){$("#housingReferralModal").modal("hide"),Fire.$emit("caseeHousingReferralsChanged"),Toast.fire({icon:"success",title:"Added successfully"}),e.$Progress.finish()})).catch((function(r){e.$Progress.fail(),console.log(r)}))},updateHousingReferral:function(){var e=this;this.$Progress.start(),this.housingReferralForm.put("/api/housing-referrals/"+this.housingReferralForm.id).then((function(){Fire.$emit("caseeHousingReferralsChanged"),$("#housingReferralModal").modal("hide"),Swal.fire("Updated!","It has been updated.","success"),e.$Progress.finish()})).catch((function(){e.$Progress.fail()}))}},created:function(){this.getCasee(this.caseeId),this.getReferralSources(),this.getNationalities(),this.getReferralReasons(),this.getHousingGrantStatuses()}};var l=a(1900);const u=(0,l.Z)(i,(function(){var e=this,r=e.$createElement,a=e._self._c||r;return a("div",{staticClass:"modal fade",attrs:{id:"housingReferralModal",tabindex:"-1","aria-labelledby":"housingReferralModalLabel","aria-hidden":"true"}},[a("div",{staticClass:"modal-dialog modal-dialog-centered"},[a("div",{staticClass:"modal-content"},[a("div",{staticClass:"modal-header"},[a("h5",{directives:[{name:"show",rawName:"v-show",value:!e.housingReferralEditMode,expression:"!housingReferralEditMode"}],staticClass:"modal-title",attrs:{id:"housingReferralModalLabel"}},[e._v("Create New Housing Referral")]),e._v(" "),a("h5",{directives:[{name:"show",rawName:"v-show",value:e.housingReferralEditMode,expression:"housingReferralEditMode"}],staticClass:"modal-title",attrs:{id:"housingReferralModalLabel"}},[e._v("Edit Housing Referral")]),e._v(" "),e._m(0)]),e._v(" "),a("form",{on:{submit:function(r){r.preventDefault(),e.housingReferralEditMode?e.updateHousingReferral():e.createHousingReferral()}}},[a("div",{staticClass:"modal-body"},[a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referral_source_id"}},[e._v("Referral Source")]),e._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.referral_source_id,expression:"housingReferralForm.referral_source_id"}],staticClass:"form-control",class:{"is-invalid":e.housingReferralForm.errors.has("referral_source_id")},attrs:{name:"referral_source_id",id:"referral_source_id"},on:{change:function(r){var a=Array.prototype.filter.call(r.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.housingReferralForm,"referral_source_id",r.target.multiple?a:a[0])}}},[a("option",{attrs:{value:"0",disabled:""}},[e._v("Choose...")]),e._v(" "),e._l(e.referralSources,(function(r){return a("option",{key:r.id,domProps:{value:r.id}},[e._v(e._s(r.name))])}))],2),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referral_source_id"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referral_date"}},[e._v("Referral Date")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.referral_date,expression:"housingReferralForm.referral_date"}],staticClass:"form-control",attrs:{id:"referral_date",type:"text",name:"referral_date",autocomplete:"off",placeholder:"YYYY-MM-DD"},domProps:{value:e.housingReferralForm.referral_date},on:{input:function(r){r.target.composing||e.$set(e.housingReferralForm,"referral_date",r.target.value)}}}),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referral_date"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referring_person_name"}},[e._v("Referring Person Name")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.referring_person_name,expression:"housingReferralForm.referring_person_name"}],staticClass:"form-control",attrs:{id:"referring_person_name",type:"text",name:"referring_person_name"},domProps:{value:e.housingReferralForm.referring_person_name},on:{input:function(r){r.target.composing||e.$set(e.housingReferralForm,"referring_person_name",r.target.value)}}}),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referring_person_name"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referring_person_email"}},[e._v("Referring Person Email")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.referring_person_email,expression:"housingReferralForm.referring_person_email"}],staticClass:"form-control",attrs:{id:"referring_person_email",type:"email",name:"referring_person_email"},domProps:{value:e.housingReferralForm.referring_person_email},on:{input:function(r){r.target.composing||e.$set(e.housingReferralForm,"referring_person_email",r.target.value)}}}),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referring_person_email"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referral_narrative_reason"}},[e._v("Referral Narrative Reason")]),e._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.referral_narrative_reason,expression:"housingReferralForm.referral_narrative_reason"}],staticClass:"form-control",attrs:{rows:"3",name:"referral_narrative_reason"},domProps:{value:e.housingReferralForm.referral_narrative_reason},on:{input:function(r){r.target.composing||e.$set(e.housingReferralForm,"referral_narrative_reason",r.target.value)}}}),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referral_narrative_reason"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"referral_source_id"}},[e._v("Grant Status")]),e._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.grant_status_id,expression:"housingReferralForm.grant_status_id"}],staticClass:"form-control",class:{"is-invalid":e.housingReferralForm.errors.has("grant_status_id")},attrs:{name:"referral_source_id",id:"grant_status_id"},on:{change:function(r){var a=Array.prototype.filter.call(r.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(e.housingReferralForm,"grant_status_id",r.target.multiple?a:a[0])}}},[a("option",{attrs:{value:"0",disabled:""}},[e._v("Choose...")]),e._v(" "),e._l(e.housingGrantStatuses,(function(r){return a("option",{key:r.id,domProps:{value:r.id}},[e._v(e._s(r.name))])}))],2),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"referral_source_id"}})],1),e._v(" "),a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-label",attrs:{for:"grant_amount"}},[e._v("Grant Amount")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.housingReferralForm.grant_amount,expression:"housingReferralForm.grant_amount"}],staticClass:"form-control",attrs:{id:"grant_amount",type:"number",name:"grant_amount"},domProps:{value:e.housingReferralForm.grant_amount},on:{input:function(r){r.target.composing||e.$set(e.housingReferralForm,"grant_amount",r.target.value)}}}),e._v(" "),a("HasError",{attrs:{form:e.housingReferralForm,field:"grant_amount"}})],1)]),e._v(" "),a("div",{staticClass:"modal-footer"},[a("button",{staticClass:"btn btn-danger",attrs:{type:"button","data-dismiss":"modal"}},[e._v("Close")]),e._v(" "),a("button",{directives:[{name:"show",rawName:"v-show",value:!e.housingReferralEditMode,expression:"!housingReferralEditMode"}],staticClass:"btn btn-success",attrs:{type:"submit"}},[e._v("Create")]),e._v(" "),a("button",{directives:[{name:"show",rawName:"v-show",value:e.housingReferralEditMode,expression:"housingReferralEditMode"}],staticClass:"btn btn-primary",attrs:{type:"submit"}},[e._v("Update")])])])])])])}),[function(){var e=this,r=e.$createElement,a=e._self._c||r;return a("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[a("span",{attrs:{"aria-hidden":"true"}},[e._v("×")])])}],!1,null,null,null).exports;a(115);const d={name:"caseHousingReferrals",components:{Multiselect:n(),HousingIntakeModal:u},mixins:[o.Z],props:{caseeId:{required:!0}},data:function(){return{casee:"",caseeHousingReferrals:[],housingReferralEditMode:!1,selectedHousingReferral:{}}},methods:{showCreateHousingIntakeModal:function(){this.housingReferralEditMode=!1,this.selectedHousingReferral={},$("#housingIntakeModal").modal("show")},showEditHousingIntakeModal:function(e){this.housingReferralEditMode=!0,this.selectedHousingReferral=e,$("#housingIntakeModal").modal("show")}},created:function(){var e=this;this.getCasee(this.caseeId),this.getCaseeHousingReferrals(this.caseeId),Fire.$on("caseeHousingReferralsChanged",(function(){e.getCaseeHousingReferrals(e.caseeId)}))}};var c=a(3379),f=a.n(c),g=a(2990),m={insert:"head",singleton:!1};f()(g.Z,m);g.Z.locals;const _=(0,l.Z)(d,(function(){var e=this,r=e.$createElement,a=e._self._c||r;return a("div",[a("div",{staticClass:"card-body"},[a("div",{staticClass:"row ml-2"},[a("button",{staticClass:"btn btn-success btn-sm mr-2",on:{click:e.showCreateHousingIntakeModal}},[a("i",{staticClass:"fas fa-plus-circle"}),e._m(0)]),e._v(" "),a("button",{staticClass:"btn btn-secondary btn-sm",on:{click:function(r){return e.getCaseeHousingReferrals(e.caseeId)}}},[a("i",{staticClass:"fas fa-sync-alt"})])]),e._v(" "),a("div",{staticClass:"row mt-3"},[a("p",{directives:[{name:"show",rawName:"v-show",value:!e.caseeHousingReferrals.length,expression:"!caseeHousingReferrals.length"}],staticClass:"font-italic ml-5"},[e._v("This case has no referrals!")]),e._v(" "),a("table",{directives:[{name:"show",rawName:"v-show",value:e.caseeHousingReferrals.length,expression:"caseeHousingReferrals.length"}],staticClass:"table table-hover table-sm"},[e._m(1),e._v(" "),this.caseeHousingReferrals?a("tbody",e._l(this.caseeHousingReferrals,(function(r){return a("tr",{key:r.id},[a("td",[e._v(e._s(r.referral_source.name))]),e._v(" "),a("td",[e._v(e._s(e._f("myDateShort")(r.referral_date)))]),e._v(" "),a("td",[a("span",{directives:[{name:"show",rawName:"v-show",value:"Accepted"==r.grant_status.name,expression:"referral.grant_status.name == 'Accepted'"}],staticClass:"badge badge-pill badge-success"},[e._v(e._s(r.grant_status.name))]),e._v(" "),a("span",{directives:[{name:"show",rawName:"v-show",value:"Rejected"==r.grant_status.name,expression:"referral.grant_status.name == 'Rejected'"}],staticClass:"badge badge-pill badge-danger"},[e._v(e._s(r.grant_status.name))]),e._v(" "),a("span",{directives:[{name:"show",rawName:"v-show",value:"Pending"==r.grant_status.name,expression:"referral.grant_status.name == 'Pending'"}],staticClass:"badge badge-pill badge-warning"},[e._v(e._s(r.grant_status.name))])]),e._v(" "),a("td",[e._v(e._s(r.grant_amount))]),e._v(" "),a("td",[e._v(e._s(r.assigned_housing_advocate.name))]),e._v(" "),a("td",[a("a",{staticClass:"clickable",on:{click:function(a){return e.showEditHousingIntakeModal(r)}}},[a("i",{staticClass:"fas fa-pencil-alt blue mr-2"})]),e._v(" "),e._m(2,!0)])])})),0):e._e()])])]),e._v(" "),e.casee?a("HousingIntakeModal",{attrs:{caseeId:e.casee.id,"v-if":e.selectedHousingReferral.id,housingReferralEditMode:e.housingReferralEditMode,selectedHousingReferral:e.selectedHousingReferral},on:{caseeHousingReferralsChanged:function(r){return e.getCasee(e.caseeId)}}}):e._e()],1)}),[function(){var e=this,r=e.$createElement,a=e._self._c||r;return a("span",[a("b",[e._v(" Referral")])])},function(){var e=this,r=e.$createElement,a=e._self._c||r;return a("thead",[a("tr",[a("th",[e._v("Source")]),e._v(" "),a("th",[e._v("Referral Date")]),e._v(" "),a("th",[e._v("Grant Status")]),e._v(" "),a("th",[e._v("Grant Amount")]),e._v(" "),a("th",[e._v("Assigned Housing Advocate")]),e._v(" "),a("th",[e._v("Actions")])])])},function(){var e=this.$createElement,r=this._self._c||e;return r("a",{staticClass:"clickable"},[r("i",{staticClass:"fa fa-trash red"})])}],!1,null,"571d6b26",null).exports}}]);
//# sourceMappingURL=caseHousingIntakes.js.map