"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[613],{102:(e,t,s)=>{s.r(t),s.d(t,{default:()=>r});var a=s(5714);const i={mixins:[s(5480).Z],data:function(){return{editMode:!1,serviceTypes:[],form:new a.ZP({id:"",name:""})}},methods:{showCreateServiceTypeModal:function(){this.editMode=!1,this.form.reset(),$("#serviceModal").modal("show")},createServiceType:function(){var e=this;this.$Progress.start(),this.form.post("/api/service-types").then((function(){Fire.$emit("servicesTypesChanged"),$("#serviceModal").modal("hide"),Toast.fire({icon:"success",title:"Added successfully"}),e.$Progress.finish()})).catch((function(){e.$Progress.fail()}))},showEditServiceTypeModal:function(e){this.editMode=!0,this.form.reset(),$("#serviceModal").modal("show"),this.form.fill(e)},updateServiceType:function(){var e=this;this.$Progress.start(),this.form.put("/api/service-types/"+this.form.id).then((function(){Fire.$emit("servicesTypesChanged"),$("#serviceModal").modal("hide"),Swal.fire("Updated!","It has been updated.","success"),e.$Progress.finish()})).catch((function(){e.$Progress.fail()}))},deleteServiceType:function(e){var t=this;Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then((function(s){s.isConfirmed&&(t.$Progress.start(),t.form.delete("/api/service-types/"+e).then((function(){Fire.$emit("servicesTypesChanged"),Swal.fire("Deleted!","It has been deleted.","success"),t.$Progress.finish()})).catch((function(){Swal("Failed!","There was something wrong.","warning")})))}))}},created:function(){var e=this;this.getServiceTypes(),Fire.$on("servicesTypesChanged",(function(){e.getServiceTypes()}))}};const r=(0,s(1900).Z)(i,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("div",{staticClass:"card-body"},[s("div",{staticClass:"form-inline ml-2"},[s("button",{staticClass:"btn btn-success btn-sm mr-2",on:{click:e.showCreateServiceTypeModal}},[s("i",{staticClass:"fas fa-plus-circle"}),e._m(0)]),e._v(" "),s("button",{staticClass:"btn btn-secondary btn-sm mr-5",on:{click:e.getServiceTypes}},[s("i",{staticClass:"fas fa-sync-alt"})]),e._v(" "),s("select",{staticClass:"custom-select my-1 mr-sm-2",attrs:{id:"inlineFormCustomSelectPref"},on:{change:e.getNationalities}},[s("option",{attrs:{value:"-1",disabled:""}},[e._v("Worker...")])])]),e._v(" "),s("div",{staticClass:"row mt-3"},[s("table",{staticClass:"border table table-hover"},[e._m(1),e._v(" "),e.serviceTypes?s("tbody",e._l(e.serviceTypes,(function(t){return s("tr",{key:t.id},[s("td",[e._v(e._s(t.name))]),e._v(" "),s("td",[s("span",{staticClass:"clickable mr-2",on:{click:function(s){return e.showEditServiceTypeModal(t)}}},[s("i",{staticClass:"fas fa-pencil-alt blue"})]),e._v(" "),s("span",{staticClass:"clickable mr-2",on:{click:function(s){return e.deleteServiceType(t.id)}}},[s("i",{staticClass:"fa fa-trash red"})])])])})),0):e._e()])])]),e._v(" "),s("div",{staticClass:"modal fade",attrs:{id:"serviceModal",tabindex:"-1","aria-labelledby":"serviceModalLabel","aria-hidden":"true"}},[s("div",{staticClass:"modal-dialog modal-dialog-centered"},[s("div",{staticClass:"modal-content"},[s("div",{staticClass:"modal-header"},[s("h5",{directives:[{name:"show",rawName:"v-show",value:!e.editMode,expression:"!editMode"}],staticClass:"modal-title",attrs:{id:"serviceModalLabel"}},[e._v("Create New Service Type")]),e._v(" "),s("h5",{directives:[{name:"show",rawName:"v-show",value:e.editMode,expression:"editMode"}],staticClass:"modal-title",attrs:{id:"serviceModalLabel"}},[e._v("Edit Service")]),e._v(" "),e._m(2)]),e._v(" "),s("form",{on:{submit:function(t){t.preventDefault(),e.editMode?e.updateServiceType():e.createServiceType()}}},[s("div",{staticClass:"modal-body"},[s("div",{staticClass:"form-group"},[s("label",{staticClass:"form-label",attrs:{for:"name"}},[e._v("Name")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.name,expression:"form.name"}],staticClass:"form-control",attrs:{id:"name",type:"text",name:"name"},domProps:{value:e.form.name},on:{input:function(t){t.target.composing||e.$set(e.form,"name",t.target.value)}}}),e._v(" "),s("HasError",{attrs:{form:e.form,field:"name"}})],1)]),e._v(" "),s("div",{staticClass:"modal-footer"},[s("button",{staticClass:"btn btn-danger",attrs:{type:"button","data-dismiss":"modal"}},[e._v("Close")]),e._v(" "),s("button",{directives:[{name:"show",rawName:"v-show",value:!e.editMode,expression:"!editMode"}],staticClass:"btn btn-success",attrs:{type:"submit"}},[e._v("Create")]),e._v(" "),s("button",{directives:[{name:"show",rawName:"v-show",value:e.editMode,expression:"editMode"}],staticClass:"btn btn-primary",attrs:{type:"submit"}},[e._v("Update")])])])])])])])}),[function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("span",[s("b",[e._v(" Service Type")])])},function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("thead",[s("tr",[s("th",[e._v("Name")]),e._v(" "),s("th",[e._v("Modify")])])])},function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[s("span",{attrs:{"aria-hidden":"true"}},[e._v("×")])])}],!1,null,null,null).exports}}]);
//# sourceMappingURL=serviceTypes.js.map