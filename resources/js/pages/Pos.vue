<template>
    <div class="row">
        <div class=" col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" class=" form-control" placeholder="ຄົ້ນຫາ..." v-model="search" @keyup.enter="GetStore()">
                </div>
            </div>
            <div class="row" style=" overflow: auto; height: 68vh;" >
                <div class="col-md-3  cursor-pointer" v-for=" list in StoreData.data" :key="list.id" >
                    <div class="card mb-3" @click="AddProduct(list.id)">
                        <span v-for="item in listOrder" :key="item.id">
                            <span class="num-list" v-if="list.id == item.id" > {{ item.order_amount }} </span>
                        </span>
                    <img class="card-img-top img-cover" :src="url + '/assets/img/' + list.image" v-if="list.image" alt="image product">
                    <img class="card-img-top img-cover" :src="url + '/assets/img/no_img.jpg'" v-else alt="image product">
                    <div class="card-body p-1 text-center">
                        {{  list.name  }}
                        <p class="card-text">
                            {{ formatPrice(list.price_sell) }} ກີບ
                        </p>
               
                    </div>
                    </div>
                </div>
            </div>
            <!-- <Pagination :pagination="StoreData" :offset="4" @paginate="GetStore($event)" /> -->
        </div>
        <div class="col-md-4">
            <div class="card" style=" overflow: auto; height: 80vh;">
                <div class="card-body">
                    <div>
          <label lass="form-label">ຊື່ລູກຄ້າ:</label>
          <input type="text" class="form-control mb-2" placeholder="..." v-model="customer_name" >
          <label lass="form-label">ເບີໂທ:</label>
          <input type="text" class="form-control" placeholder="..." v-model="customer_tel" >
                        <hr>
                        <div class=" d-flex justify-content-between fs-4 text-info fw-bold">

                            <span>ລວມຍອດເງິນ:</span>
                            <span>{{ formatPrice(TotalAmount) }} ກີບ</span>
                        </div>

                        <button type="button" class="btn btn-info w-100 fs-5 fw-bold " @click="Pay()" :disabled="!TotalAmount">ຊຳລ່ະເງິນ</button>

    <table class="table border mt-3">
      <thead>
        <tr>
          <th>ລາຍການ</th>
          <th>ລາຄາ</th>
          <th>ຍອດລວມ</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <tr v-for="list in listOrder" :key="list.id">
          <td>{{ list.name }}</td>
          <td class=" text-end">{{ formatPrice(list.price_sell) }} <br> 
            <i class='bx bxs-no-entry fs-5 text-warning cursor-pointer' @click="RemoveProduct(list.id)" ></i> {{ formatPrice(list.order_amount) }} <i class='bx bxs-plus-circle fs-5 text-success cursor-pointer' @click="AddProduct(list.id)"></i> | <i class='bx bxs-x-circle fs-5 text-danger cursor-pointer' @click="DeleteProduct(list.id)"></i></td>
         
          <td class=" text-end">{{ formatPrice(list.order_amount*list.price_sell) }}</td>
         
        </tr>
       
      </tbody>
    </table>
        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dialog_pay" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel1">ການຊຳລະເງິນ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class=" d-flex justify-content-between">
                    <span>ລວມຍອດເງິນ:</span>
                    <span>{{ formatPrice(TotalAmount) }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between">
                    <span>ຮັບເງິນນຳລູກຄ້າ:</span>
                    <span>{{ formatPrice(CashAmount) }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between text-danger" v-if="CashAmount-TotalAmount>0">
                    <span>ເງິນທອນ:</span>
                    <span>{{ formatPrice(CashAmount-TotalAmount) }} ກີບ</span>
                  </div>

                  <div class=" d-flex justify-content-center">

                        <div class="row" style=" width: 250px; ">
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(1)" >1</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(2)" >2</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(3)" >3</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(4)" >4</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(5)" >5</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(6)" >6</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(7)" >7</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(8)" >8</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(9)" >9</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum('00')" >00</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(0)" >0</button>
                            </div>
                            <div class=" col-md-4 mt-2">
                                <button class="btn btn-danger" style=" width: 60px;" @click="AddNum('-')" ><i class='bx bx-left-arrow-alt'></i></button>
                            </div>


                        </div>

                        </div>
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" :disabled="!(CashAmount>=TotalAmount)" @click="ConfirmPay()" >ຍືນຍັນຊຳລ່ະເງິນ</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
                  
                </div>
              </div>
            </div>
          </div>

</template>

<script>
import { useStore } from '../store/auth';
import axios from 'axios';
export default {
    name: 'Minipos11Pos',
    setup(){
      const store = useStore()
      return {store}
    },
    data() {
        return {
            search:'',
            list_num: 15,
            Sort:"asc",
            StoreData:[],
            loading_table: false,
            url: window.location.origin,
            listOrder:[],
            CashAmount:'',
            customer_name:'',
            customer_tel:''
        };
    },

    mounted() {
        
    },
    computed:{
        TotalAmount(){
            let calculate = this.listOrder.reduce((num,item)=> parseInt(num) + (parseInt(item.price_sell) * parseInt(item.order_amount)),0)
            return calculate
        }
    },
    methods: {
       async openLink(link){
            const response = await fetch(`${link}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}});
            const html = await response.text();
            const blob = new Blob([html],{ type: "text/html"});
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, "_blank");
        },
        Pay(){
            $("#dialog_pay").modal("show")
        },
        ConfirmPay(){
            axios.post("api/transection/add",{
                customer_name: this.customer_name,
                customer_tel: this.customer_tel,
                listorder: this.listOrder,
                acc_type: 'income'
            },{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                if(res.data.success){

                    // ເຄຼຍຂໍ້ມູນຈາກຟອມ
                    this.customer_name = ""
                    this.customer_tel = ""
                    this.listOrder = []
                    this.CashAmount = ""
                    this.GetStore()

                    // ປິດ Dialog
                    $("#dialog_pay").modal("hide")

                    this.openLink(window.location.origin+"/api/bills/print/"+res.data.bill_id)

                    this.$swal({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: res.data.message,
                                    showConfirmButton: false,
                                    timer: 3000
                            })
                } else {
                    this.$swal({
                                    // toast: true,
                                    position: 'center',
                                    icon: 'error',
                                    title: 'ຜິດຜາດ!',
                                    text: res.data.message,
                                    showConfirmButton: false,
                                    timer: 6500
                            })
                }
            }).catch((err)=>{
                console.log(err)
                if(err){
                            if(err.response.status == 401){
                                this.store.remove_token()
                                this.store.remove_user()
                                localStorage.removeItem("web_token")
                                localStorage.removeItem("web_user")
                                this.$router.push("/login")
                            }
                        }
            })
        },
        AddNum(num){
            if(num == "-"){
                this.CashAmount = this.CashAmount.slice(0,-1)
            } else {
                this.CashAmount = this.CashAmount + num
            }
        },
        AddProduct(id){
            //console.log(id)
            let item = this.StoreData.data.find((i)=>i.id == id)

            // console.log(item)
            if(item.amount>0){

                let list_order_item = this.listOrder.find((i)=>i.id == id)
                if(list_order_item){

                    let old_list_order = this.listOrder.find((i)=>i.id == id)

                    if((item.amount - old_list_order.order_amount)>0){
                        list_order_item.order_amount++
                    } else {
                        this.$swal({
                                    // toast: true,
                                    position: 'center',
                                    icon: 'error',
                                    title: 'ບໍ່ສາມາດຂາຍໄດ້!',
                                    text: 'ສິນຄ້າດັ່ງກ່າວໝົດໃນສະຕ໋ອກ',
                                    showConfirmButton: false,
                                    timer: 6500
                            })
                    }
                    


                } else {
                    this.listOrder.push({
                        id: item.id,
                        name: item.name,
                        order_amount: 1,
                        price_sell: item.price_sell
                    })
                }
            } else {
                this.$swal({
                                    // toast: true,
                                    position: 'center',
                                    icon: 'error',
                                    title: 'ບໍ່ສາມາດຂາຍໄດ້!',
                                    text: 'ສິນຄ້າດັ່ງກ່າວໝົດໃນສະຕ໋ອກ',
                                    showConfirmButton: false,
                                    timer: 6500
                            })
            }
            
            
        },
        RemoveProduct(id){

            let list_order = this.listOrder.find((i)=>i.id == id)
            if(list_order){
                list_order.order_amount--
                if(list_order.order_amount<1){
                    this.listOrder.splice(this.listOrder.map((i)=>i.id).indexOf(id),1)
                }
            }

        },
        DeleteProduct(id){
            this.listOrder.splice(this.listOrder.map((i)=>i.id).indexOf(id),1)
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        GetStore(page){
            this.loading_table = true
            axios.get(`api/store?page=${page}&sort=${this.Sort}&list_num=${this.list_num}&search=${this.search}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                this.loading_table = false
                this.StoreData = res.data
            }).catch((err)=>{
                this.loading_table = false
                console.log(err)
                if(err){
                            if(err.response.status == 401){
                                this.store.remove_token()
                                this.store.remove_user()
                                localStorage.removeItem("web_token")
                                localStorage.removeItem("web_user")
                                this.$router.push("/login")
                            }
                        }
            })

        }
    },
    created(){
        this.GetStore()
    },
    watch:{
        search(){
            if(this.search == ''){
                this.GetStore()
            }
        }
    }
};
</script>

<style scoped>
    .img-cover{
        width: 100%;
        height: 120px;
        object-fit: cover;
        object-position: center;
    }

    .num-list{
        position: absolute;
    background: blue;
    color: white;
    padding: 5px;
    border-radius: 0px 10px;
    right: 0px;
    }
</style>