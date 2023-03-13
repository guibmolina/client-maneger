<template>
   <v-container class="fill-height">
    <v-responsive class="d-flex align-center text-center fill-height">
      <v-card class="mx-auto" color="grey-lighten-3">
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="clients"
            class="rounded"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-dialog
                  v-model="dialog"
                  max-width="800px"
                >
                  <template v-slot:activator="{ props }">
                    <v-row align='start' justify="space-between">
                      <v-col cols="2">
                        <v-card color="#9C27B0">
                            <v-btn block color="#FFFF" v-bind="props">Novo cliente</v-btn>
                          
                        </v-card>
                      </v-col>
                      <v-col cols="7">
                        <v-row align='start' justify="end">
                          <v-col cols='2'>
                            <v-select
                              label="Filtar por"
                              v-model="select"
                              :items="[{label:'Nome', value:1}, {label:'CPF', value:2}, {label:'Ambos', value:3}]"
                              item-title="label"
                              item-value="value"
                              variant="solo"
                              density="compact"
                              @update:modelValue="onChange($event)"
                            ></v-select>

                          </v-col>
                          <v-col cols="5" v-if="(select == 1 || select == 3)">
                            <v-text-field
                              v-model="searchName"
                              density="compact"
                              variant="solo"
                              label="Nome"
                              append-inner-icon="mdi-magnify"
                              single-line
                              hide-details
                              @update:modelValue="onSearchName($event)"
                            ></v-text-field>
                          </v-col> 
                          <v-col cols="5" v-if="(select == 2) || (select == 3)">
                            <v-text-field
                              v-model="searchCPF"
                              density="compact"
                              type="number"
                              variant="solo"
                              label="CPF"
                              append-inner-icon="mdi-magnify"
                              single-line
                              hide-details
                              @update:modelValue="onSearchCPF($event)"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </template>
                  <v-card>
                    <v-form @submit.prevent ref="form">
                    <v-card-title>
                      <span class="text-h5">Novo Cliente</span>
                    </v-card-title>
                    <v-card-text>

                      <v-container>
                        <v-row>
                          <v-col cols="6">
                            <v-text-field
                              v-model="newclient.name"
                              label="Nome"
                              :rules="nameRules"
                              required
                            ></v-text-field>
                          </v-col>
                          <v-col cols="6">
                            <v-text-field
                              v-model="newclient.cpf"
                              :rules="cpfRules"
                              label="CPF"
                              type="number"
                              :error-messages="errorCPFMsg"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="6">
                            <v-text-field
                              v-model="newclient.birth_date"
                              label="Data de nascimento"
                              type="date"
                              :rules="dateRules"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="6">
                            <v-text-field
                            type="number"
                              v-model="newclient.phone"
                              label="Telefone"
                              :rules="phoneRules"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="close"
                      >
                        Cancelar
                      </v-btn>
                      <v-btn
                        color="blue-darken-1"
                        variant="text"
                        type="submit"
                        @click="save"

                      >
                        Salvar
                      </v-btn>
                    </v-card-actions>
                  </v-form>
                  </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="550px">
                  <v-card>
                    <v-card-title class="text-h5">Você tem certeza que deseja excluir esse item?</v-card-title>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancelar</v-btn>
                      <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon
                size="small"
                @click="deleteItem(item.raw)"
              >
                mdi-delete
              </v-icon>
            </template>
            <template v-slot:no-data>
            </template>
            <template v-slot:bottom></template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-responsive>
  </v-container>
  <div class="text-red" :v-show="errorMsg">{{errorMsg}}</div>
</template>

<script>
export default {
    data: () => ({
        debounce: null,
        errorMsg: null,
        errorCPFMsg:'',
        newclient: {},
        clientToDelete: {},
        dialog: false,
        bothFilters: false,
        select: 1,
        dialogDelete: false,
        searchName: null,
        searchCPF: null,
        headers: [{
                title: 'Nome',
                key: 'name',
                sortable: false
            },
            {
                title: 'CPF',
                key: 'cpf',
                sortable: false
            },
            {
                title: 'Data de nascimento',
                key: 'birth_date',
                sortable: false
            },
            {
                title: 'Telefone',
                key: 'phone',
                sortable: false
            },
            {
                title: 'Ações',
                key: 'actions',
                sortable: false
            },
        ],
        clients: [],
        cpfRules: [
            cpf => {
                if (!cpf) return 'CPF obrigatório'

                cpf = cpf.replace(/[^\d]+/g, '');

                if (cpf == '') return 'CPF inválido';
                // Elimina CPFs invalidos conhecidos	
                if (cpf.length != 11 ||
                    cpf == "00000000000" ||
                    cpf == "11111111111" ||
                    cpf == "22222222222" ||
                    cpf == "33333333333" ||
                    cpf == "44444444444" ||
                    cpf == "55555555555" ||
                    cpf == "66666666666" ||
                    cpf == "77777777777" ||
                    cpf == "88888888888" ||
                    cpf == "99999999999")
                    return 'CPF inválido';
                // Valida 1o digito	
                let add = 0;
                for (let i = 0; i < 9; i++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                let rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(9)))
                    return 'CPF inválido';
                // Valida 2o digito	
                add = 0;
                for (let i = 0; i < 10; i++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(10)))
                    return 'CPF inválido';
                return true;
            },
        ],

        phoneRules: [
            phone => {
                if (!phone) return true;

                if (phone.match(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)) {
                    return true;
                }

                return 'Telefone inválido';
            }
        ],
        dateRules: [
            date => {
                if (!date) return 'Data de nascimento obrigatória';

                return true
            }
        ],
        nameRules: [
            name => {
                if (!name) return 'Nome obrigatório';

                return true
            }
        ]
    }),

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    created() {
        this.initialize()
    },

    methods: {
        initialize() {
            this.$http.get('clients')
                .then(res => {
                    this.clients = res.data;
                })

        },
        deleteItem(item) {
            this.clientToDelete = item
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            this.errorMsg = null;
            this.$http.delete('clients/' + this.clientToDelete.id)
                .then(() => {
                    this.initialize()
                })
                .catch(() => {
                          this.errorMsg = 'Houve uma falha ao deletar o clinete';
                          return false
                        });

            this.closeDelete()
        },

        close() {
            this.newclient = {}
            this.dialog = false
        },

        closeDelete() {
            this.dialogDelete = false
        },

        async save() {
          this.errorCPFMsg = '';
          this.errorMsg = null;
            return this.validate()
                .then((res) => {
                    if (!res) {
                        return false
                    }
                    this.$http.post('clients/',
                            this.newclient
                        )
                        .then(() => {
                            this.initialize()
                            this.close();
                        })
                        .catch(res => {
                          if (res.response.data.error.client) {
                            this.errorCPFMsg = 'CPF já existe';
                            return false;
                          }
                          this.errorMsg = 'Houve uma falha ao salvar o cliente';
                          return false
                        });
                    
                })


        },
        async validate() {
            const {
                valid
            } = await this.$refs.form.validate()
            return valid;
        },
        onChange() {
            this.searchName = null;
            this.searchCPF = null;
        },

        onSearchName(event) {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(() => {
                let queryString = 'name=' + event;
                if (this.searchCPF) {
                    queryString += '&cpf=' + this.searchCPF;
                }
                this.$http.get('clients?' + queryString)
                    .then(res => {
                        console.log(res.data);
                        this.clients = res.data;
                    })
            }, 1000);
        },
        onSearchCPF(event) {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(() => {
                let queryString = 'cpf=' + event;
                if (this.searchName) {
                    queryString += '&name=' + this.searchName;
                }
                this.$http.get('clients?' + queryString)
                    .then(res => {
                        this.clients = res.data;
                    })

            }, 1000);
        },
    },
}
</script>