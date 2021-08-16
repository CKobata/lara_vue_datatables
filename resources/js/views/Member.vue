<template>
<div>
    <b-form inline class="mb-4 float-right">
      <b-button variant="warning" @click="initDB" class="mr-2">DB初期化</b-button>
    </b-form>

    <b-form inline class="mb-2">
      <label for="per_page" class="mr-2">表示</label>
      <b-form-select
      v-model="perPage"
      id="per_page"
      :options="['10', '30', '50', '100']"
      :value="perPage"
      class="mr-2"
      ></b-form-select>
      <span class="mr-4">件</span>

      <label for="sort_by_select" class="mr-2">ソート</label> 
      <b-form-select
      v-model="sortBy"
      id="sort_by_select"
      :options="sortOptions"
      class="mr-2"
      ></b-form-select>
      <b-form-select
      v-model="sortDesc"
      :options="[ {value: false, text: 'asc'}, {value: true, text: 'desc'}]"
      class="mr-5"
      ></b-form-select>
    </b-form>

    <b-form inline class="mb-2">
      <label for="filter" class="mr-2">検索</label>
      <b-form-input id="filter" class="mr-2" v-model="filter"></b-form-input>

      <label for="filterOn" class="mr-2">検索項目</label>
      <b-form-checkbox-group v-model="filterOn" class="" id="filterOn">
      <b-form-checkbox value="id">id</b-form-checkbox>
      <b-form-checkbox value="member_number">member_number</b-form-checkbox>
      <b-form-checkbox value="last_name">last_name</b-form-checkbox>
      <b-form-checkbox value="first_name">first_name</b-form-checkbox>
      <b-form-checkbox value="birthday">birthday</b-form-checkbox>
      <b-form-checkbox value="email">email</b-form-checkbox>
      <b-form-checkbox value="tel">tel</b-form-checkbox>
      <b-form-checkbox value="join_date">join_date</b-form-checkbox>
      <b-form-checkbox value="leave_date">leave_date</b-form-checkbox>
      </b-form-checkbox-group>
    </b-form>
    <b-form inline class="mb-4">
      <b-button variant="primary" @click="addData" class="mr-2">新規追加</b-button>
      <b-button variant="primary" @click="deleteData" class="mr-4">チェックしたものを削除</b-button>
      <p class="text-danger mt-3">{{ message }}</p>
    </b-form>
    
    <b-table 
    id="datatable"
    :items="members_disp"
    :fields="fields"
    :current-page="currentPage"
    :per-page="perPage"
    :filter="filter"
    :filter-included-fields="filterOn"
    :sort-by.sync="sortBy"
    :sort-desc.sync="sortDesc"
    :sticky-header=true
    sticky-header="600px"
    :no-border-collapse=true
    primary-key="id"
    responsive="sm"
    head-variant="dark"
    striped bordered small hover
    >
      <template #cell(actions)="row">
        
        <div class="form-inline">
        <b-form-checkbox
          size="sm"
          v-model="checkDelete[row.item.id]"
          value="1"
          unchecked-value="0"
        ></b-form-checkbox>

        <b-button 
          size="sm" 
          variant="primary" 
          @click="editData(row.item, row.index, $event.target)" 
          class="mr-0"
        >編集
        </b-button>
          </div>
      </template>
    </b-table>

    <p class="float-md-left">{{ rows + '件中 ' +　((currentPage - 1) * perPage + 1) + '～' + lastNum }} 件表示</p>


    <b-pagination class="mt-3" align="end" 
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="datatable"
    ></b-pagination>

    <b-modal 
      id="modal-add" 
      ref="modal-add"
      :title="modalAdd.title" 
      size="lg" 
      scrollable
      hide-footer
      
    >
      <validation-observer v-slot="{ handleSubmit }">
      <b-form @submit.prevent="handleSubmit(addSubmit)">
        <div class="form-inline mb-3">
          <validation-provider name="member_number" rules="required:true|min:3" v-slot="validationContext">
            <b-form-group label="member number" label-for="member_number">
              <b-form-input
                id="member_number"
                v-model="add.member_number"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_member_number"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_member_number">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
            
          <validation-provider name="dept_id" rules="required:true" v-slot="validationContext">
            <b-form-group label="dept" label-for="dept_id" class="ml-3">
              <b-form-select 
                id="dept_id"
                v-model="add.dept_id"
                :options="deptsOptions"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_dept_id"
              ></b-form-select>
              <b-form-invalid-feedback id="feedback_dept_id">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
        </div>

        <div class="form-inline mb-3">
          <validation-provider name="first_name" rules="required:true" v-slot="validationContext">
            <b-form-group label="first name" label-for="first_name">
              <b-form-input
                id="first_name"
                v-model="add.first_name"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_first_name"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_first_name">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider name="last_name" rules="required:true" v-slot="validationContext">
            <b-form-group label="last name" label-for="last_name" class="ml-3">
              <b-form-input
                id="last_name"
                v-model="add.last_name"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_last_name"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_last_name">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
        </div>

        <div class="form-inline mb-3">
          <validation-provider name="birthday" rules="required:true" v-slot="validationContext">
            <b-form-group label="birthday" label-for="birthday">
              <b-form-input
                id="birthday"
                v-model="add.birthday"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_birthday"
                 placeholder="yyyy/mm/dd"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_birthday">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider name="sex" rules="required:true" v-slot="validationContext">
            <b-form-group label="sex" label-for="sex" class="ml-3">
              <b-form-select
                v-model="add.sex"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_sex">
                <b-form-select-option value="null">Please select</b-form-select-option>
                <b-form-select-option value="1">Male</b-form-select-option>
                <b-form-select-option value="2">Female</b-form-select-option>
              </b-form-select>
              <b-form-invalid-feedback id="feedback_sex">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
        </div>

        <div class="form-inline mb-3">
          <validation-provider name="email" rules="required:true" v-slot="validationContext">
            <b-form-group label="email" label-for="email">
              <b-form-input
                id="email"
                v-model="add.email"
                :state="getValidationState(validationContext)"
                type="email"
                class="ml-1"
                aria-describedby="feedback_email"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_email">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider name="tel" rules="required:true" v-slot="validationContext">  
            <b-form-group label="tel" label-for="tel" class="ml-3">
              <b-form-input
                id="tel"
                v-model="add.tel"
                :state="getValidationState(validationContext)"
                class="ml-1"
                aria-describedby="feedback_tel"
              ></b-form-input>
              <b-form-invalid-feedback id="feedback_tel">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
        </div>

        <div class="form-inline mb-3">
          <b-form-group label="join date" label-for="join_date">
            <b-form-datepicker 
              id="join_date" 
              v-model="add.join_date"
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              class="ml-1"
            ></b-form-datepicker>
          </b-form-group>

          <b-form-group label="leave date" label-for="leave_date" class="ml-3">
            <b-form-datepicker 
              id="leave_date" 
              v-model="add.leave_date"
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              class="ml-1"
            ></b-form-datepicker>
          </b-form-group>
        </div>
        <p>&nbsp;<br />&nbsp;<br />&nbsp;</p>
        <b-button variant="primary" class="" @click="$bvModal.hide('modal-add')">Close</b-button>
        <b-button variant="primary" type="submit">Submit</b-button>
      </b-form>
      </validation-observer>
    </b-modal>
</div>
</template>

<script>
  export default {
    data() {
      return {
        filter: '',
        filterOn: [],
        perPage: 10,
        currentPage: 1,
        sortBy: 'id',
        sortDesc: false,
        fields: [
          { key: 'id', sortable: true, label: 'id', stickyColumn: true },
          { key: 'actions', label: '削除・編集' },
          { key: 'member_number', sortable: true, label: 'member_number'},
          { key: 'last_name', sortable: true, label: 'last_name' },
          { key: 'first_name', sortable: true, label: 'first_name' },
          { key: 'sex', 
            sortable: true, 
            label: 'sex',
            formatter: (value, key, item) => {
                if (value == 1) {
                  return 'M';
                } else if (value == 2) {
                  return 'F';
                }
            } 
          },
          { key: 'birthday', sortable: true, label: 'birthday' },
          { key: 'email', sortable: true, label: 'email' },
          { key: 'dept_id', 
            sortable: true, 
            label: 'dept',
            formatter: (value, key, item) => {
              return this.deptsName[value];
            } 
          },
          { key: 'tel', sortable: true, label: 'tel' },
          { key: 'join_date', sortable: true, label: 'join_date' },
          { key: 'leave_date', sortable: true, label: 'leave_date' },
        ],
        members: [],
        members_disp : [],
        depts : [],
        deptsName: [],
        deptsOptions: [],
        addId: 0,
        add: {
          id: '',
          member_number: '',
          dept_id: '',
          last_name: '',
          first_name: '',
          birthday: '',
          sex: '',
          email: '',
          tel: '',
          join_date: '',
          leave_date: ''
        },
        nameState: null,
        modalAdd: {
          title: '',
        },
        checkDelete: [],
        message: '',
      }
    },
    created() {
      axios.get('/api/member')
        .then(response => {
          this.members = response.data.members
          this.members_disp = JSON.parse(JSON.stringify(this.members));
          this.depts = response.data.depts
          this.deptsName = this.makeDeptsName(this.depts)
          this.deptsOptions = this.makeDeptsOptions(this.depts)
          console.log('created')
        })
        .catch(error => {console.log(error) 
        });
    },
    computed: {
      sortOptions() {
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      },
      rows () {
        return this.members_disp.length
      },
      lastNum () {
        let p = parseInt(this.members_disp.length / this.perPage);
        if (this.currentPage == p) {
          if (this.members_disp.length < (this.currentPage * this.perPage)) {
            return this.members_disp.length
          }
        }
        return (this.currentPage * this.perPage)
      },
      maxId () {
        // 最大値を取得
        return Math.max.apply(null,this.members_disp.map(function(o){return o.id;}))
      }
    },
    methods: {
      deleteData: function () {
        // チェックボックスのvalue=1のidを取得
        let checkedIds = [];
        for (const key in this.checkDelete) {
          if (this.checkDelete[key] == 1) {
            checkedIds.push(key)
          }
        }

        // 送信し、物理削除に成功したidを取得する
        axios.post('/api/member/delete', {ids : checkedIds})
          .then((response) => {
            if (response.data.mode == 'DELETE') {
              for (const key in response.data.member_delete) {
                // 配列の何番目に該当するか取得し、削除
                let index = this.members_disp.findIndex(i => i.id ==  response.data.member_delete[key])
                this.members_disp.splice(index, 1);
              }
              this.message = response.data.msg
            }
          })
          .catch((e) => {
            alert(e.response.data)
          })
      },
      addData: function () {
        this.resetModal()
        this.modalAdd.title = '追加'
        this.$refs['modal-add'].show()
      },
      editData: function (item, index, button) {
        this.modalAdd.title = '編集'
        for (const key in item){
          this.add[key] = item[key]
        }
        this.$refs['modal-add'].show()
      },
      addSubmit(){
        console.log(this.add.id)
        axios.post('/api/member/add', this.add)
          .then((response) => {
            if (response.data.mode == 'INSERT') {
              this.members.push(response.data.member_add)
              this.members_disp.push(response.data.member_add)

            } else if (response.data.mode == 'UPDATE') {
              const targetMember = this.members_disp.find((v) => v.id === response.data.member_add.id);
              for (const key in targetMember) {
                targetMember[key] = response.data.member_add[key]
              }
            }
            this.message = response.data.msg
          })
          .catch((e) => {
            alert(e.response.data)
          })

        this.$nextTick(() => {
          this.$bvModal.hide('modal-add')
        })
      },
      getValidationState({ dirty, validated, valid = null }) {
        return dirty || validated ? valid : null;
      },
      resetModal: function () {
        for (const key in this.add) {
          if (key == 'dept_id' || key == 'sex' ) {
            this.add[key] = null
          } else {
            this.add[key] = ''
          }
        }
      },
      makeDeptsName: function (depts) {
        let deptsName = [];
        depts.forEach(v => {
          deptsName[v.dept_id] = v.dept_name;
        });
        return deptsName
      },
      makeDeptsOptions: function (depts) {
        let options = [{value: null, text: 'Please select'}]
        depts.forEach(v => {
          options.push({value: v.dept_id, text: v.dept_name})
        });
        return options
      },
      initDB: function () {
        axios.get('/api/member/init')
        .then(response => {
          this.members = response.data.members
          this.members_disp = JSON.parse(JSON.stringify(this.members));
          this.depts = response.data.depts
          this.deptsName = this.makeDeptsName(this.depts)
          this.deptsOptions = this.makeDeptsOptions(this.depts)
          this.message = response.data.msg
        })
        .catch(error => {console.log(error) 
        });       
      }
    }
  }
</script>

<style>

</style>