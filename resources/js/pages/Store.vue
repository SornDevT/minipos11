<template>
    <div>
        <div class="card">
  <h5 class="card-header">ລາຍການ ສະຕ໋ແກສິນຄ້າ
        <div class="spinner-grow spinner-grow-sm text-success" v-if="loading" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </h5> 
  <div class="card-body">

    <div v-if="ShowForm">
        <div class=" d-flex justify-content-end">
            <button type="button" class="btn btn-success me-2" :disabled="CheckForm" @click="SaveStore()" >
                <span v-if="loading">
                    <div class="spinner-border spinner-border-sm text-white" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div> loading...
                </span>
                <span v-else> ບັນທຶກ </span>
            </button>
            <button type="button" class="btn btn-secondary" @click="CancelStore()">ຍົກເລີກ</button>
        </div>
        <div class="row"> 
            <div class="col-md-4">Image</div>
            <div class="col-md-8"> {{ FormStore }}
                <hr>
                <label  class="form-label fs-6">ຊື່ສິນຄ້າ: <span class=" text-danger">*</span>  </label>
                <input type="text" class="form-control mb-2" v-model="FormStore.name"  placeholder="....." >

                <label  class="form-label fs-6">ຈຳນວນ: <span class=" text-danger">*</span></label>
                <input type="text" class="form-control mb-2" v-model="FormStore.amount" placeholder="....." >
                <div class="row">
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຊື້: <span class=" text-danger">*</span></label>
                <input type="text" class="form-control mb-2" v-model="FormStore.price_buy" placeholder="....." >
                    </div>
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຂາຍ: <span class=" text-danger">*</span></label>
                <input type="text" class="form-control mb-2" v-model="FormStore.price_sell" placeholder="....." >
                    </div>
                </div>
           
            </div>
        </div>
    </div>


    <div v-if="!ShowForm" class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex align-items-center">
                <i class='bx bx-sort-up fs-4 me-2'></i>
      
          <select id="defaultSelect" class="form-select">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
          </select>
     
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" placeholder="ຄົ້ນຫາ....">
                <button type="button" class="btn btn-primary" @click="AddStore()"> <i class='bx bx-user-plus'></i> ເພີ່ມຂໍ້ມູນ </button>
            </div>
        </div>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>ຮູບພາບ</th>
            <th>ຊື່ສິນຄ້າ</th>
            <th>ລາຄາຊື້</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <span class="fw-medium">Angular Project</span></td>
            <td>Albert Cook</td>
            <td>
             ສສສສສ
            </td>
            <td><span class="badge bg-label-primary me-1">Active</span></td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


  </div>
</div>
    </div>
</template>

<script>
import axios from 'axios';
import { useStore } from '../store/auth';
export default {
    name: 'Minipos11Store',
    setup(){
      const store = useStore()
      return {store}
    },
    data() {
        return {
            ShowForm:false,
            FormStore:{
                name:'',
                amount:'',
                price_buy:'',
                price_sell:''
            },
            FormType: true,
            loading:false
        };
    },

    mounted() {
        
    },

    computed:{
        CheckForm(){
            if(this.FormStore.name == '' || this.FormStore.amount == '' || this.FormStore.amount == '' || this.FormStore.price_buy == '' || this.FormStore.price_sell == ''){
                return true
            } else {
                if(this.loading){
                    return true
                } else {
                    return false
                }
                
            }
        }
    },

    methods: {
        AddStore(){
            this.FormStore.name = ''
            this.FormStore.amount = ''
            this.FormStore.price_buy = ''
            this.FormStore.price_sell = ''
            this.ShowForm = true
            this.FormType = true
        },
        CancelStore(){
            this.ShowForm = false
        },
        SaveStore(){

            if(this.FormType){
                // ເພີ່ມຂໍ້ມູນໃໝ່
                console.log('Go!!')
                this.loading = true
                setTimeout(()=>{
                    console.log('Run.....')
                    axios.post("api/store/add",this.FormStore,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                        this.ShowForm = false
                        this.loading = false
                        if(res.data.success){

                        } else {

                        }

                    }).catch((err)=>{
                        this.loading = false
                        console.log(err);
                        if(err){
                            if(err.response.status == 401){
                                this.store.remove_token()
                                this.store.remove_user()
                                localStorage.removeItem("web_token")
                                localStorage.removeItem("web_user")
                                this.$router.push("/login")
                            }
                        }
                    });

                },3000)

                


            } else {
                // ອັບເດດຂໍ້ມູນ
            }

        }
    },
};
</script>

<style lang="scss" scoped>

</style>