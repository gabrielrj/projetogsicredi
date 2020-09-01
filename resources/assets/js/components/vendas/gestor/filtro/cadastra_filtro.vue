<template>
    <div>
        <div id="modalCadastroFiltro" class="modal modal-fixed-footer">
            <vue-loading :loading="form.loading" :is-full-page="true"></vue-loading>

            <div class="modal-content">
                <h4>{{form.filtro.id == 0 || form.filtro.id == null ? 'Cadastrar novo filtro' : 'Alterar filtro'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novalidate>
                    <div class="row">
                        <p>
                            Filtro por equipe ou usuário?
                        </p>
                    </div>

                    <div class="row">
                        <div class="col custom-col">
                            <label>
                                <input tipo="materialize"  name="filtro_para"
                                       class="with-gap"
                                       type="radio"
                                       id="rdFiltroParaEquipe"
                                       :disabled="form.loading"
                                       :value="form.FiltroPor.EQUIPE"
                                       v-model="form.filtroPara" />
                                <span>Equipe</span>
                            </label>
                        </div>
                        <div class="col custom-col">
                            <label>
                                <input tipo="materialize"  name="filtro_para"
                                       class="with-gap"
                                       type="radio"
                                       id="rdFiltroParaUsuario"
                                       :disabled="form.loading"
                                       :value="form.FiltroPor.USUARIO"
                                       v-model="form.filtroPara"/>
                                <span>Usuário</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12" v-if="form.filtroPara == form.FiltroPor.EQUIPE">
                            <select name="sel_lista_equipes" id="sel_lista_equipes"
                                    v-model="form.filtro.equipe_id"
                                    :disabled="form.loading">
                                <option value="" disabled>Selecione a equipe.</option>
                                <option v-for="(equipe, equipe_i) in retornaEquipesDoUsuarioLogadoOuTodasEmCasoDeUsuarioSemEquipe"
                                        :title="equipe.nome"
                                        :key="equipe_i"
                                        :value="equipe.id">{{equipe.nome}}</option>
                            </select>
                        </div>
                        <div class="input-field col s12" v-else-if="form.filtroPara == form.FiltroPor.USUARIO">
                            <select name="sel_lista_usuarios" id="sel_lista_usuarios"
                                    v-model="form.filtro.usuario_id"
                                    :disabled="form.loading">
                                <option value="" disabled>Selecione o usuário.</option>
                                <option v-for="(funcionario, funcionario_i) in retornaUsuariosDaEquipeDoUsuarioLogadoOuTodosCasoUsuarioSemEquipe"
                                        :title="funcionario.nome"
                                        :key="funcionario_i"
                                        :value="funcionario.usuario.id">{{funcionario.nome}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-panel">
                        <h5>Informações da ficha</h5>

                        <div class="row">
                            <div class="input-field col m6 s12">
                                <p class="range-field">
                                    <input type="range" id="idade_minima" min="0" max="100"
                                           v-model="form.filtro.idade" />
                                    <label for="idade_minima">Idade mínima</label>
                                </p>
                            </div>
                            <div class="input-field col m6 s12">
                                <p class="range-field">
                                    <input type="range" id="idade_maxima" min="0" max="100"
                                           v-model="form.filtro.idade_max" />
                                    <label for="idade_maxima">Idade máxima</label>
                                </p>
                            </div>
                        </div>

                        <div class="card-panel">
                            <h5>Matrículas:</h5>

                            <div class="row">
                                <div class="input-field col m4 s12">
                                    <select name="sel_lista_esferas" id="sel_lista_esferas"
                                            v-model="form.selecao.esfera"
                                            :disabled="form.loading">
                                        <option value="" disabled>Selecione a esfera.</option>
                                        <option v-for="(esfera, esfera_i) in retornaEsferas"
                                                :title="esfera"
                                                :key="esfera_i"
                                                :value="esfera">{{esfera}}</option>
                                    </select>
                                    <label>Esfera de atuação</label>
                                </div>
                                <div class="input-field col m4 s12">
                                    <select name="sel_lista_cargos" id="sel_lista_cargos"
                                            v-model="form.selecao.cargo"
                                            :disabled="form.loading">
                                        <option v-if="form.selecao.esfera == null" value="" disabled>Selecione primeiro a esfera.</option>
                                        <option v-else value="" disabled>Selecione o cargo.</option>
                                        <option v-for="(cargo, cargo_i) in retornaCargosPorEsfera"
                                                :title="cargo"
                                                :key="cargo_i"
                                                :value="cargo">{{cargo}}</option>
                                    </select>
                                    <label>Cargo</label>
                                </div>
                                <div class="input-field col m4 s12">
                                    <select name="sel_lista_situacoes" id="sel_lista_situacoes"
                                            v-model="form.selecao.situacao"
                                            :disabled="form.loading">
                                        <option v-if="form.selecao.cargo == null" value="" disabled>Selecione primeiro o cargo.</option>
                                        <option v-else value="" disabled>Selecione a situação.</option>
                                        <option v-for="(situacao, situacao_i) in retornaSituacoesPorCargo"
                                                :title="situacao"
                                                :key="situacao_i"
                                                :value="situacao">{{situacao}}</option>
                                    </select>
                                    <label>Situação da matrícula</label>
                                </div>
                                <div class="col s12">
                                    <a :disabled="form.selecao.esfera == null"
                                       @click="adicionaMatriculas"
                                       class="btn btn-small waves-effect waves-light blue darken-3">
                                        <i class="material-icons left">add</i>
                                        Adicionar
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <p v-if="form.filtro.filtro_cargos.length == 0"
                                       class="label-info-nao-encontrada">Nenhum filtro por matrículas adicionados.</p>
                                    <table v-else
                                           class="responsive-table centered striped">
                                        <thead>
                                            <tr>
                                                <th>Esfera de atuação</th>
                                                <th>Cargo</th>
                                                <th>Situação da matrícula</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(filtro_cargo, i) in form.filtro.filtro_cargos">
                                                <td>{{filtro_cargo.esfera}}</td>
                                                <td>{{filtro_cargo.cargo == null || filtro_cargo.cargo == '' ? 'Todos os cargos' : filtro_cargo.cargo}}</td>
                                                <td>{{filtro_cargo.situacao == null || filtro_cargo.situacao == '' ? 'Todos as situações' : filtro_cargo.situacao}}</td>
                                                <td>
                                                    <a class="grey-text text-darken-2" style="cursor: pointer;"
                                                       @click="excluiMatriculas(i)"><i class="material-icons">delete</i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card-panel">
                            <h5>Regiões:</h5>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <select name="sel_filtro_estados" id="sel_filtro_estados"
                                            :disabled="form.loading"
                                            v-model="form.selecao.estado">
                                        <option value="" disabled>Selecione um estado(uf)</option>
                                        <option v-for="(estadoBr, estadoBr_i) in form.estadosDoBrasil"
                                                :key="'uf_' + estadoBr_i"
                                                :title="estadoBr.sigla"
                                                :value="estadoBr.id">{{ estadoBr.nome }}</option>
                                    </select>
                                    <label>Estado (UF)</label>
                                </div>

                                <div class="input-field col m6 s12">
                                    <select multiple
                                            name="sel_filtro_cidades" id="sel_filtro_cidades"
                                            v-model="form.selecao.cidades"
                                            :disabled="form.loading">
                                        <option v-if="form.selecao.estado == null" value="" disabled>Selecione primeiro o estado.</option>
                                        <option v-else value="" disabled>Selecione a cidade.</option>
                                        <option v-for="(cidade, cidade_i) in retornaCidadesPorEstado"
                                                :title="cidade"
                                                :key="cidade_i"
                                                :value="cidade">{{cidade}}</option>
                                    </select>
                                </div>

                                <div class="col s12">
                                    <a :disabled="form.selecao.estado == null"
                                       @click="adicionaRegioes"
                                       class="btn btn-small waves-effect waves-light blue darken-3">
                                        <i class="material-icons left">add</i>
                                        Adicionar
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <p v-if="form.filtro.filtro_cidades.length == 0"
                                       class="label-info-nao-encontrada">Nenhum filtro por regiões adicionados.</p>
                                    <table v-else
                                           class="responsive-table centered striped">
                                        <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Cidade</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(filtro_cidade, i) in form.filtro.filtro_cidades">
                                            <td>{{retornaEstadoPorId(filtro_cidade.ufs_id).nome}}</td>
                                            <td>{{filtro_cidade.cidade == null || filtro_cidade.cidade == '' ? 'Todas as cidades' : filtro_cidade.cidade}}</td>
                                            <td>
                                                <a class="grey-text text-darken-2" style="cursor: pointer;"
                                                   @click="excluiRegioes(i)"><i class="material-icons">delete</i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <p>
                                Somente fichas com informações atualizadas via Confirme Online?
                            </p>
                        </div>

                        <div class="row">
                            <div class="col custom-col">
                                <label>
                                    <input tipo="materialize"  name="somente_atualizados_confirme"
                                           class="with-gap"
                                           type="radio"
                                           id="rdSomenteAtualizadosConfirme"
                                           :disabled="form.loading"
                                           :value="true"
                                           v-model="form.filtro.somente_atualizados_confirme" />
                                    <span>Sim</span>
                                </label>
                            </div>
                            <div class="col custom-col">
                                <label>
                                    <input tipo="materialize"  name="somente_atualizados_confirme"
                                           class="with-gap"
                                           type="radio"
                                           id="rdFichasComOuSemConfirme"
                                           :disabled="form.loading"
                                           :value="false"
                                           v-model="form.filtro.somente_atualizados_confirme"/>
                                    <span>Não</span>
                                </label>
                            </div>
                        </div>

                        <div class="card-panel">
                            <h5>Informações financeiras:</h5>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <money id="renda" ref="renda" name="renda"
                                           v-model="form.filtro.renda"
                                           v-bind="form.mask_money"></money>
                                    <label for="renda">Renda mínima</label>
                                </div>
                                <div class="input-field col m6 s12">
                                    <money id="margem" ref="margem" name="margem"
                                           v-model="form.filtro.margem"
                                           v-bind="form.mask_money"></money>
                                    <label for="margem">Margem mínima</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <select name="sel_filtro_bancos_recebimento" id="sel_filtro_bancos_recebimento"
                                            :disabled="form.loading"
                                            v-model="form.selecao.banco_recebimento">
                                        <option value="" disabled>Selecione um banco de recebimento</option>
                                        <option v-for="(banco, banco_i) in bancosRecebimento"
                                                :key="'banco_rec_' + banco_i"
                                                :title="banco.banco"
                                                :value="banco.banco">{{ banco.banco }}</option>
                                    </select>
                                    <label>Banco de recebimento</label>
                                </div>

                                <div class="col m6 s12">
                                    <a :disabled="form.selecao.banco_recebimento == null"
                                       @click="adicionaBancoDeRecebimento"
                                       class="btn btn-small waves-effect waves-light blue darken-3">
                                        <i class="material-icons left">add</i>
                                        Adicionar
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <p v-if="form.filtro.filtro_bancos_recs.length == 0"
                                       class="label-info-nao-encontrada">Nenhum filtro por bancos de recebimento adicionados.</p>
                                    <table v-else
                                           class="responsive-table centered striped">
                                        <thead>
                                        <tr>
                                            <th>Banco de recebimento</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(filtro_banco,i) in form.filtro.filtro_bancos_recs">
                                            <td>{{filtro_banco.banco}}</td>
                                            <td>
                                                <a class="grey-text text-darken-2" style="cursor: pointer;"
                                                   @click="excluiBancoDeRecimento(i)"><i class="material-icons">delete</i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <p>
                                Somente fichas com empréstimos?
                            </p>
                        </div>

                        <div class="row">
                            <div class="col custom-col">
                                <label>
                                    <input tipo="materialize"  name="somente_com_emprestimos"
                                           class="with-gap"
                                           type="radio"
                                           id="rdSomenteComEmprestimos"
                                           :disabled="form.loading"
                                           :value="true"
                                           v-model="form.filtro.somente_com_emprestimos" />
                                    <span>Sim</span>
                                </label>
                            </div>
                            <div class="col custom-col">
                                <label>
                                    <input tipo="materialize"  name="somente_com_emprestimos"
                                           class="with-gap"
                                           type="radio"
                                           id="rdFichasComOuSemEmprestimos"
                                           :disabled="form.loading"
                                           :value="false"
                                           v-model="form.filtro.somente_com_emprestimos"/>
                                    <span>Não</span>
                                </label>
                            </div>
                        </div>

                        <div class="card-panel">
                            <h5>Empréstimos:</h5>

                            <div class="row">
                                <div class="input-field col m4 s12">
                                    <money id="parcela" ref="parcela" name="parcela"
                                           v-model="form.filtro.parcela"
                                           v-bind="form.mask_money"></money>
                                    <label for="parcela">Parcela mínima de empréstimo</label>
                                </div>
                                <div class="input-field col m4 s12">
                                    <p class="range-field">
                                        <input type="range" id="qtd_par_totais" min="0" max="100"
                                               v-model="form.filtro.qtd_par_totais" />
                                        <label for="qtd_par_totais">Quantidade mínima de parcelas totais</label>
                                    </p>
                                </div>
                                <div class="input-field col m4 s12">
                                    <p class="range-field">
                                        <input type="range" id="qtd_par_rest" min="0" max="100"
                                               v-model="form.filtro.qtd_par_rest" />
                                        <label for="qtd_par_rest">Quantidade mínima de parcelas restantes</label>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <select name="sel_filtro_bancos_emprestimo" id="sel_filtro_bancos_emprestimo"
                                            :disabled="form.loading"
                                            v-model="form.selecao.banco_emprestimo">
                                        <option value="" disabled>Selecione um banco de emprestimo</option>
                                        <option v-for="(banco, banco_i) in bancosEmprestimo"
                                                :key="'banco_emp_' + banco_i"
                                                :title="banco.banco"
                                                :value="banco.banco">{{ banco.banco }}</option>
                                    </select>
                                    <label>Banco de empréstimo</label>
                                </div>

                                <div class="col m6 s12">
                                    <a :disabled="form.selecao.banco_emprestimo == null"
                                       @click="adicionaBancoDeEmprestimo"
                                       class="btn btn-small waves-effect waves-light blue darken-3">
                                        <i class="material-icons left">add</i>
                                        Adicionar
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <p v-if="form.filtro.filtro_bancos_emps.length == 0"
                                       class="label-info-nao-encontrada">Nenhum filtro por bancos de empréstimo adicionados.</p>
                                    <table v-else
                                           class="responsive-table centered striped">
                                        <thead>
                                        <tr>
                                            <th>Banco de empréstimo</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(filtro_banco, i) in form.filtro.filtro_bancos_emps">
                                            <td>{{filtro_banco.banco}}</td>
                                            <td>
                                                <a class="grey-text text-darken-2" style="cursor: pointer;"
                                                   @click="excluiBancoDeEmprestimo(i)"><i class="material-icons">delete</i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <a class="btn btn-small red darken-1 waves-effect waves-light" @click="fechaJanela">
                    <i class="material-icons left">cancel</i>
                    CANCELAR
                </a>

                <a class="btn blue darken-4 waves-effect waves-light"
                   @click="salva"
                   :disabled="form.loading">
                    <i class="material-icons left">save</i>
                    SALVAR
                </a>

            </div>
        </div>
    </div>
</template>

<script>
//import {maxLength, minLength, required, requiredIf} from "vuelidate/lib/validators";

import {Money} from 'v-money';

export default {
    name: "cadastra_filtro",
    components:{
        Money
    },
    props: {
        usuarioLogado: {
            type: Object,
            default: null,
        },
        empresa: {
            type: Object,
            default: null,
        },
        cargos: {
            type: Array,
            default: null,
        },
        estadosECidades: {
            type: Array,
            default: null,
        },
        bancosRecebimento: {
            type: Array,
            default: null,
        },
        bancosEmprestimo: {
            type: Array,
            default: null,
        }
    },
    data() {
        return {
            form: {
                atualizaCidades: false,

                estadosDoBrasil: [
                    {id: 1,  sigla: 'AC', nome: 'AC - Acre'},
                    {id: 2,  sigla: 'AL', nome: 'AL - Alagoas'},
                    {id: 3,  sigla: 'AM', nome: 'AM - Amazônia'},
                    {id: 4,  sigla: 'AP', nome: 'AP - Amapá'},
                    {id: 5,  sigla: 'BA', nome: 'BA - Bahia'},
                    {id: 6,  sigla: 'CE', nome: 'CE - Ceará'},
                    {id: 7,  sigla: 'DF', nome: 'DF - Distrito Federal (Brasília)'},
                    {id: 8,  sigla: 'ES', nome: 'ES - Espírito Santo'},
                    {id: 9,  sigla: 'GO', nome: 'GO - Goiás'},
                    {id: 10, sigla: 'MA', nome: 'MA - Maranhão'},
                    {id: 11, sigla: 'MG', nome: 'MG - Minas Gerais'},
                    {id: 12, sigla: 'MS', nome: 'MS - Mato Grosso do Sul'},
                    {id: 13, sigla: 'MT', nome: 'MT - Mato Grosso'},
                    {id: 14, sigla: 'PA', nome: 'PA - Pará'},
                    {id: 15, sigla: 'PB', nome: 'PB - Paraíba'},
                    {id: 16, sigla: 'PE', nome: 'PE - Pernambuco'},
                    {id: 17, sigla: 'PI', nome: 'PI - Piauí'},
                    {id: 18, sigla: 'PR', nome: 'PR - Paraná'},
                    {id: 19, sigla: 'RJ', nome: 'RJ - Rio de Janeiro'},
                    {id: 20, sigla: 'RN', nome: 'RN - Rio Grande do Norte'},
                    {id: 21, sigla: 'RO', nome: 'RO - Rondônia'},
                    {id: 22, sigla: 'RR', nome: 'RR - Roraima'},
                    {id: 23, sigla: 'RS', nome: 'RS - Rio Grande do Sul'},
                    {id: 24, sigla: 'SC', nome: 'SC - Santa Catarina'},
                    {id: 25, sigla: 'SE', nome: 'SE - Sergipe'},
                    {id: 26, sigla: 'SP', nome: 'SP - São Paulo'},
                    {id: 27, sigla: 'TO', nome: 'TO - Tocantins'}
                ],

                filtro: {
                    id: null,
                    equipe_id: null,
                    perfil_id: null,
                    usuario_id: null,
                    idade: 0,
                    idade_max: 0,
                    renda: 0,
                    margem: 0,
                    parcela: 0,
                    qtd_par_totais: 0,
                    qtd_par_rest: 0,
                    somente_com_emprestimos: false,
                    somente_atualizados_confirme: false,
                    ativo: true,
                    equipe: null,
                    perfil: null,
                    usuario: null,
                    filtro_cargos: [],
                    filtro_cidades: [],
                    filtro_situacoes: [],
                    filtro_bancos_recs: [],
                    filtro_bancos_emps: [],
                },

                selecao: {
                    esfera: null,
                    cargo: null,
                    situacao: null,
                    estado: null,
                    cidades: [],
                    banco_recebimento: null,
                    banco_emprestimo: null,
                },

                loading: false,

                FiltroPor: {
                    EQUIPE: 'EQUIPE',
                    USUARIO: 'USUARIO'
                },

                filtroPara: null,

                //Mascaras da tela
                mask_money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'R$ ',
                    suffix: '',
                    precision: 2,
                    masked: false /* doesn't work with directive */
                },
            },
            abreJanelaForm: false,
            janelaForm: null,
        }
    },
    /*validations: {
    },*/
    computed: {
        retornaEquipesDoUsuarioLogadoOuTodasEmCasoDeUsuarioSemEquipe(){
            if(this.usuarioLogado.funcionario.equipes_id == null)
                return this.empresa.equipes
            else
                return _.filter(this.empresa.equipes, (equipe) => {return equipe.id == this.usuarioLogado.funcionario.equipes_id})
        },
        retornaUsuariosDaEquipeDoUsuarioLogadoOuTodosCasoUsuarioSemEquipe(){
            if(this.usuarioLogado.funcionario.equipes_id == null)
                return this.empresa.funcionarios
            else
                return _.filter(this.empresa.funcionarios, (funcionario) => {return funcionario.equipes_id == this.usuarioLogado.funcionario.equipes_id})
        },
        retornaEsferas(){
            let esferas = []

            esferas = _.uniq(_.map(this.cargos, 'esfera'))

            return esferas
        },
        retornaCargosPorEsfera(){
            let cargos = []

            if(this.form.selecao.esfera != null && this.form.selecao.esfera != ''){
                cargos = _.filter(this.cargos, (c) => { return c.esfera == this.form.selecao.esfera})
                cargos = _.uniq(_.map(cargos, 'cargo'))
            }

            return cargos
        },
        retornaSituacoesPorCargo(){
            let situacoes = []

            if(this.form.selecao.cargo != null && this.form.selecao.cargo != ''){
                situacoes = _.filter(this.cargos, (c) => { return c.cargo == this.form.selecao.cargo})
                situacoes = _.uniq(_.map(situacoes, 'situacao'))
            }

            return situacoes
        },
        retornaCidadesPorEstado(){
            let cidades = []

            if(this.form.selecao.estado != null && this.form.selecao.estado != ''){
                cidades = _.filter(this.estadosECidades, (c) => { return c.id == this.form.selecao.estado})
                cidades = _.uniq(_.map(cidades, 'cidade'))
            }

            this.form.atualizaCidades = true

            return cidades
        },
    },
    watch: {
        abreJanelaForm(newValue) {
            if(newValue) {
                this.janelaForm.open()
            }else{
                this.janelaForm.close()
                this.janelaForm.destroy()
            }
        }
    },
    mounted() {
        $('input[type=range]').range()
        this.inicializaModalFormCadastroFiltro()
        //this.inicializaNoUiSlider()
    },
    updated() {

        M.updateTextFields()
        $('#sel_lista_equipes').formSelect()
        $('#sel_lista_usuarios').formSelect()
        $('#sel_lista_esferas').formSelect()
        $('#sel_lista_cargos').formSelect()
        $('#sel_lista_situacoes').formSelect()
        $('#sel_filtro_estados').formSelect()
        $('#sel_filtro_bancos_recebimento').formSelect()
        $('#sel_filtro_bancos_emprestimo').formSelect()

        if(this.form.atualizaCidades) {
            $('#sel_filtro_cidades').formSelect()
            this.form.atualizaCidades = false
        }
    },
    methods: {
        inicializaNoUiSlider(){
            var slider = document.getElementById('test-slider');
            noUiSlider.create(slider, {
                start: [20, 80],
                connect: true,
                step: 1,
                orientation: 'horizontal', // 'horizontal' or 'vertical'
                range: {
                    'min': 0,
                    'max': 100
                },
                format: wNumb({
                    decimals: 0
                })
            });
        },
        inicializaModalFormCadastroFiltro(){
            const elemento = document.getElementById('modalCadastroFiltro')
            //const instancia = M.Modal.init(elemento, {dismissible: false})
            const instancia = M.Modal.init(elemento, {
                dismissible: false,
                onCloseEnd: () => {
                    this.eventoDeFechamentoDeJanela()
                },
                onOpenEnd: () => {
                    $(elemento).css('transform', '')
                },
            })
            this.janelaForm = instancia
        },

        abreJanela(){
            this.abreJanelaForm = true
        },

        fechaJanela(){
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.abreJanelaForm = false
        },

        eventoDeFechamentoDeJanela(){
            this.abreJanelaForm = false
        },

        limpaFiltros(){
            this.form.selecao.esfera = null
            this.form.selecao.cargo = null
            this.form.selecao.situacao = null
            this.form.selecao.estado = null
            this.form.selecao.cidades = []
            this.form.selecao.banco_recebimento = null
            this.form.selecao.banco_emprestimo = null

            this.form.atualizaCidades = true
        },

        limpaCamposFormulario(){
            this.form.filtro.id = null
            this.form.filtro.equipe_id = null
            this.form.filtro.perfil_id = null
            this.form.filtro.usuario_id = null
            this.form.filtro.idade = 0
            this.form.filtro.idade_max = 0
            this.form.filtro.renda = 0
            this.form.filtro.margem = 0
            this.form.filtro.parcela = 0
            this.form.filtro.qtd_par_totais = 0
            this.form.filtro.qtd_par_rest = 0
            this.form.filtro.somente_com_emprestimos = false
            this.form.filtro.somente_atualizados_confirme = false
            this.form.filtro.ativo = true
            this.form.filtro.equipe = null
            this.form.filtro.perfil = null
            this.form.filtro.usuario = null
            this.form.filtro.filtro_cargos = []
            this.form.filtro.filtro_cidades = []
            this.form.filtro.filtro_situacoes = []
            this.form.filtro.filtro_bancos_recs = []
            this.form.filtro.filtro_bancos_emps = []
            this.form.loading = false
        },

        preencheDadosFiltro(filtroParaEdicao){
            this.form.filtro.id = filtroParaEdicao.id
            this.form.filtro.equipe_id = filtroParaEdicao.equipe_id
            this.form.filtro.perfil_id = filtroParaEdicao.perfil_id
            this.form.filtro.usuario_id = filtroParaEdicao.usuario_id
            this.form.filtro.idade = filtroParaEdicao.idade
            this.form.filtro.idade_max = filtroParaEdicao.idade_max
            this.form.filtro.renda = filtroParaEdicao.renda
            this.form.filtro.margem = filtroParaEdicao.margem
            this.form.filtro.parcela = filtroParaEdicao.parcela
            this.form.filtro.qtd_par_totais = filtroParaEdicao.qtd_par_totais
            this.form.filtro.qtd_par_rest = filtroParaEdicao.qtd_par_rest
            this.form.filtro.somente_com_emprestimos = filtroParaEdicao.somente_com_emprestimos
            this.form.filtro.somente_atualizados_confirme = filtroParaEdicao.somente_atualizados_confirme
            this.form.filtro.ativo = filtroParaEdicao.ativo
            this.form.filtro.equipe = filtroParaEdicao.equipe
            this.form.filtro.perfil = filtroParaEdicao.perfil
            this.form.filtro.usuario = filtroParaEdicao.usuario
            this.form.filtro.filtro_cargos = filtroParaEdicao.filtro_cargos
            this.form.filtro.filtro_cidades = filtroParaEdicao.filtro_cidades
            this.form.filtro.filtro_situacoes = filtroParaEdicao.filtro_situacoes
            this.form.filtro.filtro_bancos_recs = filtroParaEdicao.filtro_bancos_recs
            this.form.filtro.filtro_bancos_emps = filtroParaEdicao.filtro_bancos_emps
        },

        limpaMensagensDeErro(){
            //this.$v.$reset()
            this.$refs.alert.limpaMensagens()
        },

        cadastraNovoFiltro(){
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()

            this.abreJanela()
        },

        editaFiltro(filtroParaEdicao){
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()

            this.preencheDadosFiltro(filtroParaEdicao)

            this.abreJanela()
        },
        valida(){
            if((this.form.filtro.equipe_id == null || this.form.filtro.equipe_id == 0) && (this.form.filtro.usuario_id == null || this.form.filtro.usuario_id == 0)){
                Swal.fire('Oops!', 'Por favor. Informe para quem será aplicado o filtro (Equipe ou Usuário).', 'error')
                return false
            }

            if(this.form.filtro.filtro_cargos.length == 0 && this.form.filtro.filtro_cidades.length == 0 &&
                (this.form.filtro.idade == null || this.form.filtro.idade == 0 || this.form.filtro.idade == '') &&
                (this.form.filtro.idade_max == null || this.form.filtro.idade_max == 0 || this.form.filtro.idade_max == '') &&
                this.form.filtro.filtro_bancos_recs.length == 0 && this.form.filtro.renda == 0 && this.form.filtro.margem == 0 &&
                this.form.filtro.filtro_bancos_emps.length == 0 && this.form.filtro.parcela == 0 &&
                (this.form.filtro.qtd_par_totais == null || this.form.filtro.qtd_par_totais == 0 || this.form.filtro.qtd_par_totais == '') &&
                (this.form.filtro.qtd_par_rest == null || this.form.filtro.qtd_par_rest == 0 || this.form.filtro.qtd_par_rest == '')){
                Swal.fire('Oops!', 'Por favor. Informe ao menos um filtro para ser aplicado.', 'error')
                return false;
            }

            return true
        },
        // Eventos de validação do formulário

        salva(){
            if(this.valida()){
                this.limpaMensagensDeErro()
                this.form.loading = true

                if(this.form.filtro.id == null || this.form.filtro.id == 0){
                    axios.post('/configuracaofiltros', this.form.filtro)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire(
                                'Ótimo!',
                                response.data.msg,
                                'success'
                            )
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            $('#modalCadastroFiltro').scrollTop(0)
                            this.form.loading = false
                            Swal.fire(
                                'Oops!',
                                'Ocorreu um erro ao tentar cadastrar esse filtro, feche para ver mais detalhes.',
                                'error'
                            )
                            this.$refs.alert.abreMensagens(error.response)
                        })
                }else{
                    axios.put('/configuracaofiltros/' + this.form.filtro.id, this.form.filtro)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire(
                                'Ótimo!',
                                response.data.msg,
                                'success'
                            )
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            $('#modalCadastroFiltro').scrollTop(0)
                            this.form.loading = false
                            Swal.fire(
                                'Oops!',
                                'Ocorreu um erro ao tentar alterar essa filtro, feche para ver mais detalhes.',
                                'error'
                            )
                            this.$refs.alert.abreMensagens(error.response)
                        })
                }
            }
        },

        concluiSalvamentoDosDados(){
            this.$emit('emissaoDeDadosSalvos')
            this.fechaJanela()
        },

        //Métodos de seleção de filtros
        adicionaMatriculas(){
            this.form.filtro.filtro_cargos.push({
                esfera: this.form.selecao.esfera,
                cargo: this.form.selecao.cargo,
                situacao: this.form.selecao.situacao,
            })

            this.form.selecao.esfera = null
            this.form.selecao.cargo = null
            this.form.selecao.situacao = null
        },
        excluiMatriculas(indice){
            this.form.filtro.filtro_cargos.splice(indice, 1)
        },
        adicionaRegioes(){
            if(this.form.selecao.cidades.length > 0){
                _.forEach(this.form.selecao.cidades, (cidade) => {
                    this.form.filtro.filtro_cidades.push({
                        ufs_id: this.form.selecao.estado,
                        cidade: cidade
                    })
                })
            }else {
                this.form.filtro.filtro_cidades.push({
                    ufs_id: this.form.selecao.estado,
                    cidade: null
                })
            }

            this.form.selecao.estado = null
            this.form.selecao.cidades = []
        },
        excluiRegioes(indice){
            this.form.filtro.filtro_cidades.splice(indice, 1)
        },
        adicionaBancoDeRecebimento(){
            this.form.filtro.filtro_bancos_recs.push({
                banco: this.form.selecao.banco_recebimento
            })

            this.form.selecao.banco_recebimento = null
        },
        excluiBancoDeRecimento(indice){
            this.form.filtro.filtro_bancos_recs.splice(indice, 1)
        },
        adicionaBancoDeEmprestimo(){
            this.form.filtro.filtro_bancos_emps.push({
                banco: this.form.selecao.banco_emprestimo
            })

            this.form.selecao.banco_emprestimo = null
        },
        excluiBancoDeEmprestimo(indice){
            this.form.filtro.filtro_bancos_emps.splice(indice, 1)
        },

        //Util
        somenteNumeros(e){
            if(e.charCode >= 48 && e.charCode <= 57)
                return
            else{
                e.preventDefault()
                return false
            }
        },
        retornaEstadoPorId(uf_id){
            return _.find(this.form.estadosDoBrasil, (estado) => {return estado.id == uf_id})
        }
    }
}
</script>

<style scoped>
    div#modalCadastroFiltro.modal{
        width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
        top: 0 !important;
    }
</style>
