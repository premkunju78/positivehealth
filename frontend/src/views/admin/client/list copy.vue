<template>
  <div>
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div v-if="!id" class="d-flex align-items-center justify-content-end" v-permission="['create-clients']">
              <!-- <b-form-input
                  v-model="searchQuery"
                  class="d-inline-block mr-1"
                  placeholder="Search..."
                /> -->
              <router-link :to="{ name: 'client-create' }">
                <b-button variant="primary" v-permission="['create-clients']">
                  <span class="text-nowrap">Add Client</span>
                </b-button>
              </router-link>
              <b-button variant="primary" @click="viewClientGroupModal()" class="ml-1" v-if="$store.getters.user.role_id == 10 || $store.getters.user.role_id == 1
                ">
                <span class="text-nowrap">Add to Client Group</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>
      <!-- Per Page -->
      <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        v-if="$store.getters.user.role_id == 1 || $store.getters.user.role_id == 2 || $store.getters.user.role.is_consultant == 1">
        <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
          <li class="nav-item">
            <a :class="tabId === 'program' ? 'nav-link active' : 'nav-link'" @click="showProgramClient()"
              id="home-tab-justified" data-bs-toggle="tab" role="tab" aria-controls="home-just"
              aria-selected="true">Program</a>
          </li>
          <li class="nav-item">
            <a :class="tabId === 'consultation' ? 'nav-link active' : 'nav-link'" id="profile-tab-justified"
              @click="showConsultationClient()" data-bs-toggle="tab" role="tab" aria-controls="profile-just"
              aria-selected="false">Consultation</a>
          </li>
        </ul>
      </b-col>
      <div class="tab-content pt-1">
        <div :class="tabId === 'program' ? 'tab-pane active' : 'tab-pane'" id="program" role="tabpanel"
          aria-labelledby="home-tab-justified" :key="key">
          <b-table ref="refUserListTable" class="position-relative" :items="items" responsive :fields="tableColumns"
            primary-key="id" :sort-by.sync="sortBy" :totalRows="totalRows" show-empty
            empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :tbody-tr-class="rowClass">
            <template v-slot:cell(selected)="data">
              <b-form-group>
                <input type="checkbox" :value="data.item.id" v-model="clientgroupData.selected" />
              </b-form-group>
            </template>
            <!-- <template #head(selected)="data">
            <b-form-group>
              <input type="checkbox" />
            </b-form-group>
          </template> -->
            <!-- Column: User -->
            <template #cell(user)="data">
              <b-media vertical-align="center">
                <template #aside>
                  <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)"
                    :variant="`light-primary`" :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
                </template>
                {{ data.item.name }}
              </b-media>
            </template>
            <template #cell(id)="data">
              {{ "CL-" + data.item.id }}
            </template>
            <template #cell(call)="data">
              <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item.phone)">
                <span class="text-nowrap">Call Now</span>
              </b-button>
            </template>
            <template #cell(status)="data">
              {{ data.item.client_status == 'Completed' ? 'Joined' : data.item.client_status }}
            </template>
            <template #cell(age)="data">
              {{ age(data.item.dob) }}
            </template>
            <template #cell(investigation)="data">
              <b-button variant="success" class="btn btn-primary ml-2" @click="
                $router.push({ name: 'investigations', params: { id: data.item.id } })
                ">
                <span class="text-nowrap">Investigation</span>
              </b-button>
            </template>



            <!-- Column: Actions -->
            <template #cell(actions)="data">
              <div class="text-nowrap" v-if="typeof $store.getters.user.detail !== 'undefined' &&
                $store.getters.user.detail.cisf == '1'
                ">
                <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL" v-permission="['view-clients']">
                  <template #button-content>
                    <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                      v-permission="['view-clients']" />
                  </template>
                  <b-dropdown-item @click="updatestatus(data.item)" v-if="data.item.client_status == 'Pending'">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">Complete</span>
                  </b-dropdown-item>
                  <b-dropdown-item @click="viewAssignQModal(data.item)">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">Assign Questionnaire</span>
                  </b-dropdown-item>
                  <b-dropdown-item @click="updatetrainingstatus(data.item)" v-permission="['view-clients']"
                    v-if="data.item.apptraining_status == 'Pending'">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">App Training Completed</span>
                  </b-dropdown-item>
                </b-dropdown>
              </div>
              <div class="text-nowrap" v-else>
                <!-- Client Modules -->
                <feather-icon :id="`client-module-${data.item.id}-id`" icon="LayersIcon" size="16" class="mx-1" @click="
                  $router.push({ name: 'client-modules', params: { id: data.item.id } })
                  " />
                <b-tooltip title="Client Modules" :target="`client-module-${data.item.id}-id`" />

                <b-dropdown v-if="$store.getters.user.role_id != 2" variant="link" no-caret
                  :right="$store.state.appConfig.isRTL" v-permission="['view-clients']">
                  <template #button-content>
                    <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                      v-permission="['view-clients']" />
                  </template>
                  <!-- <b-dropdown-item :to="{ name: 'apps-users-view', params: { id: data.item.id } }" >
                  <feather-icon icon="FileTextIcon" />
                  <span class="align-middle ml-50">Details</span>
                </b-dropdown-item> -->

                  <b-dropdown-item @click="updatestatus(data.item)" v-permission="['view-clients']" v-if="data.item.client_status == 'Pending' &&
                    $store.getters.user.role.id == 11
                    ">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">Complete</span>
                  </b-dropdown-item>

                  <b-dropdown-item :to="{
                    name: 'edit-client',
                    params: { id: data.item.id, user: data.item },
                  }" v-permission="['edit-clients']">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">Edit</span>
                  </b-dropdown-item>
                  <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteClient(data.item)"
                    v-permission="['edit-clients']">
                    <feather-icon icon="EditIcon" />
                    <span class="align-middle ml-50">Delete</span>
                  </b-dropdown-item>
                  <!-- <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['view-clients']"
                  v-if="$store.getters.user.role.id != '11'">
                  <feather-icon icon="UsersIcon" />
                  <span class="align-middle ml-50">Assign Consultant</span>
                </b-dropdown-item> -->

                  <b-dropdown-item @click="viewAssignHcModal(data.item)" v-permission="['view-clients']"
                    v-if="!data.item.hc_id">
                    <feather-icon icon="UsersIcon" />
                    <span class="align-middle ml-50">Assign Health Coach</span>
                  </b-dropdown-item>
                  <!-- <b-dropdown-item @click="viewAssignInchargeModal(data.item)" v-permission="['view-clients']"
                  v-if="!data.item.ic_id">
                  <feather-icon icon="UsersIcon" />
                  <span class="align-middle ml-50">Assign Incharge</span>
                </b-dropdown-item> -->
                  <b-dropdown-item @click="viewUserModal(data.item)" v-permission="['view-clients']">
                    <feather-icon icon="EyeIcon" />
                    <span class="align-middle ml-50">View Consultants</span>
                  </b-dropdown-item>
                  <!-- <b-dropdown-item  v-permission="['delete-users']">
                  <feather-icon icon="TrashIcon"/>
                  <span class="align-middle ml-50">Delete</span>
                </b-dropdown-item> -->
                </b-dropdown>
              </div>
            </template>
          </b-table>
          <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
            :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
            align="right" @change="handlePaginationChange">
          </b-pagination>
        </div>
        <div :class="tabId === 'consultation' ? 'tab-pane active' : 'tab-pane'" id="consultation" role="tabpanel"
          aria-labelledby="home-tab-justified" :key="key">
          <b-table ref="refUserListTable" class="position-relative" :items="consultation_items" responsive
            :fields="consultationTableColumns" primary-key="id" :sort-by.sync="sortBy" :totalRows="consultationTotalRows"
            show-empty empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
            <template #cell(user)="data">
              <b-media vertical-align="center">
                <template #aside>
                  <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)"
                    :variant="`light-primary`" :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
                </template>
                {{ data.item.name }}
              </b-media>
            </template>
            <template #cell(id)="data">
              {{ "CL-" + data.item.id }}
            </template>
            <template #cell(age)="data">
              {{ age(data.item.dob) }}
            </template>
          </b-table>
          <b-pagination v-if="cpagination.total" v-model="cpagination.page" :page.sync="cpagination.page"
            :total-rows="cpagination.total" :index="1" :per-page="cpagination.per_page" aria-controls="package-row"
            align="right" @change="handlecPaginationChange">
          </b-pagination>

          <b-table ref="refScheduleListTable" class="position-relative" :items="items" responsive :fields="tableColumns"
            primary-key="id" :sort-by.sync="sortBy" :totalRows="totalRows" show-empty
            empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
            <template #cell(name)="data">
              <span>{{ data.item.booked_by === user_id ? data.item.creator : data.item.name }}</span>
            </template>
            <!-- Column: Video -->
            <template #cell(video)="data">

              <b-button variant="info" v-permission="['create-video-meeting']"
                v-if="!data.item.host_link && (new Date(data.item.timeslot_to).getTime() >= new Date().getTime())"
                @click="generateVideoLink(data.item)">
                <span class="text-nowrap">Create Meeting</span>
              </b-button>

              <a target="_blank" v-if="data.item.host_link"
                :href="data.item.booked_by === user_id ? data.item.participant_link : data.item.host_link">
                <b-button variant="info">
                  <span class="text-nowrap primary">Join Now</span>
                </b-button>
              </a>


            </template>
            <!-- Column: Voice -->
            <template #cell(voice)="data">
              <b-button variant="success" @click="callNow(data.item)">
                <span class="text-nowrap">Call Now</span>
              </b-button>
            </template>
            <template #cell(schedule_time)="item">
              {{ moment(item.timeslot_from).format("DD-MM-YYYY hh:mm A") }} - {{
                moment(item.timeslot_to).format("DD-MM-YYYY hh:mm A") }}
            </template>
            <template #cell(duration)="item">
              {{ moment(item.timeslot_to).diff(moment(item.timeslot_from), 'minutes') }} MINS
            </template>
            <template #cell(status)="item">
              {{ item.status }}
            </template>
            <template #cell(rating)="item">
            </template>
          </b-table>

        </div>
      </div>
    </b-card>
    <b-modal v-permission="['view-clients']" id="assign" ref="assign" ok-only ok-title="Assign" @show="getRoles"
      @hidden="resetData" @ok="handleOk" centered size="lg" title="Consultant Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="alliance-partners" rules="required">
                <b-form-group label="Select Consultant Type" label-for="partners"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="userData.roles" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="roles"
                    :reduce="(val) => val.id" :clearable="false" multiple input-id="roles" @input="getConsultants" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="clients" rules="required">
                <b-form-group label="Select Consultants" label-for="clients"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="userData.consultants" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="consultants" :reduce="(val) => val.id" :clearable="false" multiple input-id="consultants" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
    <b-modal v-permission="['view-clients']" id="assignhc" ref="assignhc" ok-only ok-title="Assign"
      @show="getHealthcoaches" @hidden="resetHcData" @ok="handleHcOk" centered size="lg" title="Health Coach Assignment"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onHcAssign)">
          <b-row>
            <b-col cols="12">
              <validation-provider #default="validationContext" name="healthcoach" rules="required">
                <b-form-group label="Select Health coach" label-for="healthcoach"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="hcData.hc_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="healthcoaches" :reduce="(val) => val.id" :clearable="false" input-id="healthcoaches" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
    <b-modal v-permission="['view-clients']" id="assignincharge" ref="assignincharge" ok-only ok-title="Assign"
      @show="getIncharges" @hidden="resetInchargeData" @ok="handleInchargeOk" centered size="lg"
      title="Incharge Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onInchargeAssign)">
          <b-row>
            <b-col cols="6">
              <validation-provider #default="validationContext" name="role" rules="required">
                <b-form-group label="Select Role" label-for="role" :state="getValidationState(validationContext)">
                  <v-select v-model="inchargeData.role_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="roles" :reduce="(val) => val.id" :clearable="false" input-id="roles"
                    @input="getIncharges" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="6">
              <validation-provider #default="validationContext" name="incharge" rules="required">
                <b-form-group label="Select Incharge" label-for="incharge" :state="getValidationState(validationContext)">
                  <v-select v-model="inchargeData.ic_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="incharges" :multiple="true" :reduce="(val) => val.id" :clearable="false"
                    input-id="incharges" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
    <b-modal v-permission="['view-clients']" id="view-users" hide-footer centered size="lg" @show="getAssignedUsers"
      @hidden="resetUsers" title="Assigned Consutants" ref="view-users" no-close-on-backdrop>
      <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="list" responsive
        :fields="ucolumns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :sticky-header="true">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(role)="data">
          <span class="text-primary">{{ data.item.role.toUpperCase() }}</span>
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <a @click="handleRemoveUser('client', data.item.client_consultant_id)" v-permission="['edit-users']"
            style="cursor: pointer" title="remove">
            <feather-icon icon="TrashIcon" />
          </a>
        </template>
      </b-table>
    </b-modal>

    <b-modal id="clientgroup" ref="clientgroup" ok-only ok-title="Add" @show="getClientGroups"
      @hidden="resetClientGroupData" @ok="handleClientGroupOk" centered size="lg" title="Add to Client Group"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onClientGroupAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="client-groups" rules="required">
                <b-form-group label="Select Client Group" label-for="clientgroups"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="clientgroupData.group_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="clientgroups" :reduce="(val) => val.id" :clearable="false" input-id="clientgroups" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>

    <b-modal id="question" ref="question" ok-only ok-title="Add" @show="getQuestinaries" @hidden="resetQData"
      @ok="handleQOK" centered size="lg" title="Assign Questinaries" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onQAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="client-groups" rules="required">
                <b-form-group label="Select Questinaries" label-for="questinaries"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="QData.q_ids" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="questinaries" :reduce="(val) => val.id" :clearable="false" input-id="clientgroups"
                    :multiple="true" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BFormGroup,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BTooltip,
  VBModal,
  BModal,
  BFormInvalidFeedback,
  BFormCheckbox,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import vSelect from "vue-select";

import formValidation from "@core/comp-functions/forms/form-validation";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { required } from "@validations";

export default {
  components: {
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    VBModal,
    BModal,
    BFormInvalidFeedback,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    vSelect,
    BFormGroup,
    BFormInput,

    ValidationProvider,
    ValidationObserver,
    BFormCheckbox,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetData,
      this.resetHcData,
      this.resetInchargeData
    );
    return {
      tabId: 'program',
      items: [],
      consultation_items: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
      tableColumns: [
        { key: "id", label: "Id", sortable: true },
        { key: "name", label: "Full Name", sortable: true },
        { key: "gender", label: "Gender", sortable: true },
        { key: "age", label: "Age", sortable: true },
        // { key: 'email', label: 'Eamil', sortable: true },
        { key: "group_name", label: "Group", sortable: true },
        { key: "referred_partner", label: "Referred Partner", sortable: true },
        { key: "status", label: "Status", sortable: true },
        // { key: 'phone', label: 'Contact Number', sortable: true },
        { key: "actions" },
      ],
      consultationTableColumns: [
        { key: "id", label: "Id", sortable: true },
        { key: "name", label: "Full Name", sortable: true },
        { key: "gender", label: "Gender", sortable: true },
        { key: "age", label: "Age", sortable: true },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "role", label: "Consultant type", sortable: true },
        { key: "name", label: "Consultant name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      userData: {
        roles: [],
        consultants: [],
      },
      QData: {
        q_ids: [],
      },
      hcData: {},
      inchargeData: {
        ic_id: []
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      cpagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      roles: [],
      consultants: [],
      client: null,
      list: [],
      refFormObserver,
      getValidationState,
      resetForm,
      required,
      healthcoaches: [],
      incharges: [],
      clientgroupData: {
        selected: [],
      },
      clientgroups: [],
      questinaries: [],
    };
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
    role: {
      type: [String, null],
      default: null,
    },
  },
  created() {
    this.getList();
    this.getConsultationList();
    this.columns();
    this.getRoles();
  },
  methods: {
    age: function age(dob) {
      if (dob != '' && dob != null) {
        var diff = (new Date().getTime() - new Date(dob).getTime()) / 1000;
        diff /= 60 * 60 * 24;
        return Math.abs(Math.round(diff / 365.25));
      }
      return '';

    },
    rowClass(item, type) {
      if (item && type === "row") {
        if (item.client_status === "Pending") {
          return "tr-Pending";
        } else if (item.client_status === "Completed" && !item.hc_id) {
          return "tr-Completed";
        } else {
          return "";
        }
      } else {
        return null;
      }
    },
    columns() {
      if (this.$store.getters.user.role_id == 10) {
        this.tableColumns = [
          { key: "selected", label: "", sortable: false },
          { key: "id", label: "User Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "age", label: "Age", sortable: true },
          // { key: 'email', label: 'Eamil', sortable: true },
          { key: "group_name", label: "Group", sortable: true },
          { key: "referred_partner", label: "Referred Partner", sortable: true },
          { key: "status", label: "Status", sortable: true },
          // { key: 'phone', label: 'Contact Number', sortable: true },
          { key: "actions" },
          { key: "call", label: "Call", sortable: true },
        ];
      } else if (this.$store.getters.user.role_id == 1) {
        this.tableColumns = [
          { key: "selected", label: "", sortable: false },
          { key: "id", label: "User Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "age", label: "Age", sortable: true },
          // { key: 'email', label: 'Eamil', sortable: true },
          { key: "group_name", label: "Group", sortable: true },
          { key: "referred_partner", label: "Referred Partner", sortable: true },
          { key: "status", label: "Status", sortable: true },
          // { key: 'phone', label: 'Contact Number', sortable: true },
          { key: "actions" },
        ];
      } else if (
        this.$store.getters.user.role_id == 11 &&
        typeof this.$store.getters.user.detail !== "undefined" &&
        this.$store.getters.user.detail.cisf == "1"
      ) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "age", label: "Age", sortable: true },
          { key: "group_name", label: "Group", sortable: true },
          { key: "referred_partner", label: "Referred Partner", sortable: true },
          { key: "status", label: "Status", sortable: true },
          { key: "actions" },
          { key: "call", label: "Call", sortable: true },
        ];
      }
      if (this.$store.getters.user.role_id == 11) {
        this.ucolumns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "role", label: "Consultant type", sortable: true },
          { key: "name", label: "Consultant name", sortable: true },
          { key: "actions", label: "Action", sortable: false },
        ];
      }
      if (this.$store.getters.user.role_id == 17 || this.$store.getters.user.role_id == 18 || this.$store.getters.user.role_id == 19) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "age", label: "Age", sortable: true },
          { key: "investigation", label: "Investigation", sortable: true },
          { key: "status", label: "Status", sortable: true },
          { key: "call", label: "Call", sortable: true },
        ];
      }
    },
    async getConsultationList() {
      try {
        this.cpagination.id = this.id;
        this.cpagination.role = this.role;
        const { data } = await axios.get("/clients", {
          params: this.cpagination,
        });
        console.log(data.clients);
        this.consultation_items = data.clients;
        this.cpagination.per_page = data.per_page;
        this.cpagination.total = data.total;
        this.cpagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching client list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/clients", {
          params: this.pagination,
        });
        console.log(data.clients);
        this.items = data.clients;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching client list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    handlecPaginationChange(val) {
      this.cpagination.page = val;
      this.getConsultationList();
    },
    viewAssignModal(client) {
      this.client = client;
      this.$refs["assign"].show();
    },
    viewAssignHcModal(client) {
      this.client = client;
      this.$refs["assignhc"].show();
    },
    viewUserModal(client) {
      this.client = client;
      this.$refs["view-users"].show();
    },
    async deleteClient(client) {
      if (confirm("Do you really want to delete?")) {
        try {

          let data;
          data = await axios.delete(`/clients/${client.id}`);
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.data.message,
              icon: "EditIcon",
              variant: data.data.success ? "success" : "info"
            }
          });
          this.getList();
        } catch (error) {

        }
      }
    },
    async getRoles() {
      try {
        const { data } = await axios.get("/roles/list");
        console.log(data);
        this.roles = data.roles;
      } catch (error) {
        console.log(error);
      }
    },
    async getConsultants() {
      try {
        this.consultants = this.userData.consultants = [];
        const { data } = await axios.get("/consultants/list", {
          params: {
            roles: this.userData.roles,
            client: this.client.id,
          },
        });
        this.consultants = data.users;
      } catch (error) {
        console.log(error);
      }
    },

    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },

    resetData() {
      this.userData = {
        roles: [],
        consultants: [],
      };
    },
    handleHcOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onHcAssign);
    },

    resetHcData() {
      this.hcData = {
        hc_id: "",
      };
    },
    resetUsers() {
      this.list = [];
    },

    async onAssign() {
      try {
        this.userData.client = this.client.id;
        const { data } = await axios.post("/client/assignment", this.userData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assign"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },

    async onHcAssign() {
      try {
        this.hcData.client_id = this.client.id;
        const { data } = await axios.post("/client/hcassignment", this.hcData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assignhc"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },

    async getAssignedUsers() {
      try {
        let id = this.client.id;
        const { data } = await axios.get(`client/${id}/users`, {
          params: this.pagination,
        });
        this.list = data.users;
        this.totalRows = data.totalRows;
      } catch (err) {
        console.log(err);
      }
    },

    async handleRemoveUser(type, id) {
      try {
        const { data } = await axios.delete(`/assignment/${id}/user?type=${type}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getAssignedUsers();
      } catch (error) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: error.response.data.message,
            icon: "BellIcon",
            variant: "danger",
          },
        });
      }
    },
    async callNow(phone) {
      try {
        const { data } = await axios.post(`/coordinator/call/`, { phone: phone });
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error while initiating a call",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async getHealthcoaches() {
      try {
        this.healthcoaches = [];
        const { data } = await axios.get("/healthcoaches/list", {
          params: {},
        });
        this.healthcoaches = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async updatestatus(item) {
      try {
        const { data } = await axios.post(`/client/updatestatus/`, { id: item.id });
        this.getList();
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error while updating",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    viewClientGroupModal(client) {
      this.client = client;
      this.$refs["clientgroup"].show();
    },
    async getClientGroups() {
      try {
        this.healthcoaches = [];
        const { data } = await axios.get("/clientgroup/list", {
          params: {},
        });
        this.clientgroups = data.groups;
      } catch (error) {
        console.log(error);
      }
    },
    handleClientGroupOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onClientGroupAssign);
    },
    resetClientGroupData() {
      this.clientgroupData = {
        selected: [],
      };
    },
    async onClientGroupAssign() {
      try {
        const { data } = await axios.post(
          "/clientgroup/clientassignment",
          this.clientgroupData
        );
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["clientgroup"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    viewAssignInchargeModal(client) {
      this.client = client;
      this.$refs["assignincharge"].show();
    },
    async getIncharges() {
      try {
        this.inchargeData.ic_id = [];
        this.incharges = [];
        const { data } = await axios.get("/incharges/list", {
          params: { role_id: this.inchargeData.role_id },
        });
        this.incharges = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    resetInchargeData() {
      this.inchargeData = {
        ic_id: [],
      };
    },
    handleInchargeOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onInchargeAssign);
    },
    async onInchargeAssign() {
      try {
        this.inchargeData.client_id = this.client.id;
        const { data } = await axios.post(
          "/client/inchargeassignment",
          this.inchargeData
        );
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assignincharge"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    viewAssignQModal(client) {
      this.client = client;
      this.$refs["question"].show();
    },
    async getQuestinaries() {
      try {
        this.QData.q_ids = [];
        this.questinaries = [];
        const { data } = await axios.get(
          "/htmlquestionnaire/list?client_id=" + this.client.id
        );
        this.questinaries = data.questionnaires;
        this.QData.q_ids = data.assigned_qs;
      } catch (error) {
        console.log(error);
      }
    },
    resetQData() {
      this.QData = {
        q_ids: [],
      };
    },
    handleQOK(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onQAssign);
    },
    async onQAssign() {
      try {
        this.QData.client_id = this.client.id;
        const { data } = await axios.post("/htmlquestionnaire/assignClient", this.QData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["question"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    async updatetrainingstatus(item) {
      try {
        const { data } = await axios.post(`/client/updatetrainingstatus/`, {
          id: item.id,
        });
        this.getList();
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error while updating",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    showProgramClient() {
      this.tabId = 'program';
    },
    showConsultationClient() {
      this.tabId = 'consultation';
    }
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}

.tr-Pending {
  background: beige;
}

.tr-Completed {
  background: #58cd5c54;
  color: #000;
}
</style>
