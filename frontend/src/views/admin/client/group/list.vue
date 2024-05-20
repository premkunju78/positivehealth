<template>
  <div style="min-height: 300px">
    <b-overlay
      :show="$store.state.app.shallShowOverlay"
      no-wrap
      opacity="0.5"
      blur="1px"
    ></b-overlay>
    <b-card :title="type + ' (CLGP/' + next_group_id + ')'" v-if="$store.getters.user.role_id === 1">
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="group_name"
                rules="required"
              >
                <b-form-group label="Group Name" label-for="group_id">
                  <b-form-input
                    id="group_name-input"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.group_name"
                    :readonly="readonly"
                    :class="errors.group_name ? 'is-invalid' : ''"
                    :area-invalid="errors.group_name ? true : false"
                  />
                  <b-form-invalid-feedback v-if="errors.group_name">
                    {{ errors.group_name[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="group_type"
                rules="required"
              >
                <b-form-group label="Group Type" label-for="group_type">
                  <v-select
                    id="group_type-input"
                    :reduce="val => val.id"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.group_type"
                    :readonly="readonly"
                    :class="errors.group_type ? 'is-invalid' : ''"
                    :area-invalid="errors.group_type ? true : false"
                    :options="groupTypesOptions" 
                  >
                  </v-select>
                  <b-form-invalid-feedback v-if="errors.group_type">
                    {{ errors.group_type[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>            
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="session_no"
                rules="required"
              >
                <b-form-group label="No of Session" label-for="session_no">
                  <v-select
                    ref="groupSessionNo"
                    id="session_no-input"
                    :reduce="val => val.id"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.session_no"
                    :readonly="readonly"
                    :class="errors.session_no ? 'is-invalid' : ''"
                    :area-invalid="errors.session_no ? true : false"
                    :options="sessionNumberOptions" 
                    @input="setSessionNumber"
                  >
                  </v-select>
                  <b-form-invalid-feedback v-if="errors.session_no">
                    {{ errors.session_no[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="session_type"
                rules="required"
              >
                <b-form-group label="Session Type" label-for="session_type">
                  <v-select
                    id="session_type-input"
                    :reduce="val => val.id"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.session_type"
                    :readonly="readonly"
                    :class="errors.session_type ? 'is-invalid' : ''"
                    :area-invalid="errors.session_type ? true : false"
                    :options="sessionTypeOptions" 
                  >
                  </v-select>
                  <b-form-invalid-feedback v-if="errors.session_type">
                    {{ errors.session_type[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="no_of_participants"
                rules="required"
              >
                <b-form-group label="No of Participants" label-for="no_of_participants">
                  <b-form-input
                    id="no_of_participants-input"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.no_of_participants"
                    :readonly="readonly"
                    :class="errors.no_of_participants ? 'is-invalid' : ''"
                    :area-invalid="errors.no_of_participants ? true : false"
                  />
                  <b-form-invalid-feedback v-if="errors.no_of_participants">
                    {{ errors.no_of_participants[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="2">
              <validation-provider
                #default="validationContext"
                name="Name"
              >
                <b-form-group label="Name" label-for="name">
                  <b-form-input
                    id="name-input"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.name"
                    :readonly="readonly"
                    :class="errors.name ? 'is-invalid' : ''"
                    :area-invalid="errors.name ? true : false"
                  />
                  <b-form-invalid-feedback v-if="errors.name">
                    {{ errors.name[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <div class="sessions-box" :class="selected_session_no > 0 ? 'active' : ''">

            <b-row class="session-rows" v-for="(session_no, index) in selected_session_no" :key="index">
              <h3 class="row-header">
                <span>GS/{{ next_group_id }}/{{ session_no }}</span>
              </h3>
              <b-col cols="12" md="2">
                <!-- First Name -->
                <validation-provider
                  #default="validationContext"
                  name="Select Date"
                >
                  <b-form-group
                    label="Select Date"
                    label-for="start_date"
                    :state="getValidationState(validationContext)"
                  >
                    <flat-pickr
                      v-model="groupData.sessions.start_date[index]"
                      class="form-control"
                      :config="config.datePicker"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="2">
                <!-- First Name -->
                <validation-provider
                  #default="validationContext"
                  name="Start Time"
                >
                  <b-form-group
                    label="Start Time"
                    label-for="start_time"
                    :state="getValidationState(validationContext)"
                  >
                    <flat-pickr
                      v-model="groupData.sessions.start_time[index]"
                      class="form-control"
                      :config="config.timePicker"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="2">
                <!-- First Name -->
                <validation-provider
                  #default="validationContext"
                  name="End Time"
                >
                  <b-form-group
                    label="End Time"
                    label-for="end_time"
                    :state="getValidationState(validationContext)"
                  >
                    <flat-pickr
                      v-model="groupData.sessions.end_time[index]"
                      class="form-control"
                      :config="config.timePicker" 
                      @input="calculateDuration(index)"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="1">
                <b-form-group label="Duration" label-for="duration">
                  <b-form-input
                    ref="sessionDuration"
                    v-model="groupData.sessions.duration[index]"
                    :readonly="true"  
                  />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="2">
                <validation-provider
                  #default="validationContext"
                  name="Title"
                >
                  <b-form-group label="Title" label-for="title">
                    <b-form-input
                      :state="getValidationState(validationContext)"
                      v-model="groupData.sessions.title[index]"
                      :readonly="readonly"
                      :class="errors.title ? 'is-invalid' : ''"
                      :area-invalid="errors.title ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.title">
                      {{ errors.title[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="2">
                <validation-provider
                  #default="validationContext"
                  name="assigned_consultant"
                >
                  <b-form-group label="Assign to Consultant" label-for="assigned_consultant">
                    <v-select
                      :state="getValidationState(validationContext)"
                      v-model="groupData.sessions.assigned_consultant[index]"
                      :readonly="readonly"
                      :reduce="val => val.id"
                      :class="errors.assigned_consultant ? 'is-invalid' : ''"
                      :area-invalid="errors.assigned_consultant ? true : false"
                      :options="consultantOptions" 
                    >
                    </v-select>
                    <b-form-invalid-feedback v-if="errors.group_type">
                      {{ errors.assigned_consultant[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>            
              <b-col sm="8 mt-1" md="6">
                <div class="btn-input">
                  <validation-provider
                    #default="validationContext"
                    name="Zoom Meeting Link"
                  >
                    <b-form-group label="Zoom Meeting Link" label-for="meeting_link">
                      <b-form-input
                        ref="sessionMeetingLink"
                        :state="getValidationState(validationContext)"
                        v-model="groupData.sessions.meeting_link[index]"
                        :readonly="readonly"
                        :class="errors.meeting_link ? 'is-invalid' : ''"
                        :area-invalid="errors.meeting_link ? true : false"
                      />
                      <b-form-invalid-feedback v-if="errors.meeting_link">
                        {{ errors.meeting_link[0] }}
                      </b-form-invalid-feedback>
                      <b-form-invalid-feedback v-else>
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>
                  <b-button
                    variant="info"
                    type="button"
                    class="btn-sm"
                    @click="generateVideoLink(index)"
                  >
                    Generate Meeting link
                  </b-button>                    
                </div>
              </b-col>
            </b-row>
          </div>
          <b-row>
            <b-col sm="8 mt-1" md="4" v-if="groupData.session_type == 'paid'">
              <div class="btn-input">
                <validation-provider
                  #default="validationContext"
                  name="Enter Amount"
                >
                  <b-form-group label="Enter Amount" label-for="payment_amount">
                    <b-form-input
                      id="payment_amount-input"
                      :state="getValidationState(validationContext)"
                      v-model="groupData.payment_amount"
                      :readonly="readonly"
                      :class="errors.payment_amount ? 'is-invalid' : ''"
                      :area-invalid="errors.payment_amount ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.payment_amount">
                      {{ errors.payment_amount[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
                <b-button
                  variant="info"
                  type="button"
                  class="mr-2 btn-sm"
                  @click="generatePaymentLink(next_group_id)"
                >
                  Generate Payment link
                </b-button>                    
              </div>
            </b-col>
            <b-col cols="12" md="6" class="mt-2 mb-2">
              <!-- media -->
              <b-media no-body :class="errors.logo ? 'is-invalid' : ''" :area-invalid="errors.logo ? true : false">
                <b-media-aside>
                  <a :href="'/view/file?path=' + groupData.logo" target="_blank">
                    <b-img ref="previewLogoEl" rounded :src="'/view/file?path=' + groupData.logo" height="80" />
                  </a>
                  <!--/ avatar -->
                </b-media-aside>

                <b-media-body class="mt-75 ml-75">
                  <!-- upload button -->
                  <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                    @click="$refs.refLogoEl.$el.click()">
                    Upload Picture
                  </b-button>
                  <b-form-file ref="refLogoEl" v-model="logo" accept=".jpg, .png, .gif" :hidden="true" plain
                    @input="validateSize(logo, 'logo', inputLogoRenderer)" />
                  <!--/ upload button -->

                  <!-- reset -->
                  <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                    class="mb-75 mr-75" v-if="logo" @click="
                      $refs.previewLogoEl.src = '/view/file?path=' + groupData.logo;
                    logo = null;
                    ">
                    Reset
                  </b-button>
                  <!--/ reset -->
                  <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                </b-media-body>
              </b-media>
              <b-form-invalid-feedback v-if="errors.logo">
                {{ errors.logo[0] }}
              </b-form-invalid-feedback>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider
                #default="validationContext"
                name="Description"
              >
                <b-form-group label="Description" label-for="description">
                  <quill-editor
                    id="description"
                    v-model="groupData.description"
                    :options="editorOption"
                    :state="getValidationState(validationContext)"
                    rows="4"
                  />
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row style="margin-top: 2%">
            <!-- Action Buttons -->
            <b-button
              variant="primary"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              type="submit"
              class="mr-2"
            >
              Save Changes
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              @click="resetFormCard"
              variant="outline-secondary"
            >
              Cancel
            </b-button>
          </b-row>
        </form>
      </validation-observer>
    </b-card>
    <b-card>
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col
          cols="12"
          md="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        >
          <label>Show</label>
          <v-select
            v-model="pagination.per_page"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]"
            :clearable="false"
            class="per-page-selector d-inline-block mx-50"
          />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6"> </b-col>
      </b-row>

      <b-table
        style="min-height: 250px"
        ref="refRolesTable"
        class="position-relative"
        :items="groups"
        responsive
        :fields="columns"
        :totalRows="pagination.total"
        primaryKey="index"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: Actions -->
        <template #cell(index)="data">
          CLGP/{{ data.item.id }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <template #cell(description)="data">
          <span class="text-primary" v-html="data.item.description"></span>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret>
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
              />
            </template>
            <b-dropdown-item @click="viewGroup(data.item)">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50"> View Group</span>
            </b-dropdown-item>
            <b-dropdown-item @click="editGroup(data.item)" v-if="$store.getters.user.role_id === 1">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit Group</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewClientsAssignModal(data.item)">
              <feather-icon icon="PlusIcon" />
              <span class="align-middle ml-50">Add Clients</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewUserModal(data.item)">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View Clients</span>
            </b-dropdown-item>
            <b-dropdown-item @click="handleDeleteGroup(data.item.id)" v-if="$store.getters.user.role_id === 1">
              <feather-icon icon="TrashIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>
      <b-pagination
        v-if="pagination.total"
        v-model="pagination.page"
        :page.sync="pagination.page"
        :total-rows="pagination.total"
        :index="1"
        :per-page="pagination.per_page"
        aria-controls="package-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
      <b-modal
        v-permission="['edit-program']"
        id="view-users"
        hide-footer
        centered
        size="lg"
        @show="getAssignedUsers"
        @hidden="resetUsers"
        title="Assigned Clients"
        ref="view-users"
        no-close-on-backdrop
      >
        <b-table
          style="min-height: 250px"
          ref="refRolesTable"
          class="position-relative"
          :items="users"
          responsive
          :fields="ucolumns"
          primaryKey="index"
          :sort-by.sync="sortBy"
          show-empty
          empty-text="No matching records found"
          :sort-desc.sync="isSortDirDesc"
          :sticky-header="true"
        >
          <!-- Column: Actions -->
          <template #cell(index)="data">
            CL/{{ data.item.id }}
          </template>

          <template #cell(call)="data">
            <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item.phone)">
              <span class="text-nowrap">Call Now</span>
            </b-button>
          </template>

          <!-- Column: Actions -->
          <template #cell(actions)="data">
            <a
              @click="handleRemoveUser(data.item.mapping_id)"
              style="cursor: pointer"
              title="remove client"
            >
              <span class="text">Delete&nbsp;</span>
              <feather-icon icon="TrashIcon" />
            </a>
          </template>
        </b-table>
      </b-modal>
      
      <b-modal
        v-permission="['edit-program']"
        id="view-group-detail"
        hide-footer
        size="lg"
        title="Group Details"
        ref="view-group-detail"
        no-close-on-backdrop
      >
        <b-row>
          <b-col cols="12" md="3">
            <b-form-group label="Group Name:">
              <strong>{{ groupView.group_name }} ({{ groupView.name }})</strong>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Type:">
              <strong>{{ groupView.group_type }}</strong>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Total Sessions:">
              <strong>{{ groupView.session_no }}</strong>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Total Participants:">
              <strong>{{ groupView.no_of_participants }}</strong>
            </b-form-group>
            <b-form-group label="Available Slots:">
              <strong>{{ groupView.available_slots }}</strong>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="12">
            <b-form-group label="Detail Page Link:">
              <div class="form-control">{{ groupView.detail_link }}</div>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="12" v-if="groupView.session_type == 'paid'">
            <b-form-group label="Payment Link:">
              <div class="form-control">{{ group_payment_link }}</div>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="12">
            <b-form-group label="Description">
              <strong v-html="groupView.description"></strong>
            </b-form-group>
          </b-col>
        </b-row>          
      </b-modal>

      <b-modal id="assign-clients" ref="assign-clients" ok-only ok-title="Assign Clients"
        @show="getAlliancePartners" @hidden="resetData" @ok="handleOk" centered size="lg" title="Assign Clients"
        no-close-on-backdrop>
        <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
          <form ref="form" @submit.prevent="handleSubmit(onAssign)">
            <b-row>
              <b-col cols="12" md="12">
                <validation-provider #default="validationContext" name="alliance_partner" rules="required">
                  <b-form-group label="Select Alliance Partner" label-for="alliance-partner-id"
                    :state="getValidationState(validationContext)">
                    <v-select 
                      v-model="assign_data.alliance_partner_id" 
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" 
                      @input="getClientLists"
                      :options="alliancePartners" 
                      :reduce="(val) => val.id" 
                      :clearable="false" 
                      input-id="alliance-partner-id"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="12">
                <div class="mb-2"">
                  <b-form-group label="Select Clients" label-for="alliance-client-id">
                    <v-select
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="clientLists" 
                      v-model="assign_data.alliance_client_id" 
                      :reduce="(val) => val.id" 
                      :clearable="true" 
                      input-id="alliance-client-id"
                    />                   
                  </b-form-group>
                </div>
              </b-col>              
            </b-row>
          </form>
        </validation-observer>
      </b-modal>
    </b-card>
  </div>
</template>
<style>
iframe {
  width: 100% !important;
  height: 200px !important;
}
</style>
<script>
import {
  BCard,
  BCardText,
  BTable,
  BRow,
  BCol,
  BAvatar,
  BPagination,
  BSpinner,
  BButton,
  BDropdown,
  BDropdownItem,
  VBModal,
  BModal,
  BMedia,
  BMediaAside,
  BMediaBody,
  BFormInvalidFeedback,
  BFormInput,
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BFormFile,
  BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";

import Ripple from "vue-ripple-directive";
import { validateSize } from "@core/utils/utils";
import { ref } from "@vue/composition-api";

import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";

export default {
  components: {
    BCard,
    BCardText,
    BTable,
    BRow,
    BCol,
    BAvatar,
    BPagination,
    BSpinner,
    vSelect,
    BButton,
    BDropdown,
    BDropdownItem,
    VBModal,
    BModal,
    BMedia,
    BMediaAside,
    BMediaBody,
    ValidationProvider,
    ValidationObserver,
    BFormInvalidFeedback,
    BFormInput,
    BFormGroup,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    flatPickr,
    quillEditor,
    BFormFile,
    BImg,
  },
  data() {
    var toolbarOptions = [
      ["bold", "italic", "underline", "strike"], // toggled buttons
      ['link', 'image'],
      [{ list: "ordered" }, { list: "bullet" }],
      [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
      [{ direction: "rtl" }], // text direction
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }], // dropdown with defaults from theme
      [{ font: [] }],
      [{ align: [] }], // remove formatting button
    ];
    return {
      avatar: null,
      next_group_id: null,
      group_payment_link: null,
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      group: null,
      required,
      type: "Create Group",
      groupPackages: [],
      readonly: false,
      errors: [],
      packages: [],
      mappingData: {
        cost: {},
      },
      partners: [],
      list: [],
      logo: null,
      users: [],
      totalRows: 0,
      config: {
        datePicker: {
          wrap: true,
          enableTime: false,
          dateFormat: 'd-m-Y',
          // noCalendar: true,
        },
        timePicker: {
          wrap: true,
          enableTime: true,
          dateFormat: 'H:i:s',
          noCalendar: true,
        },
      },
      editorOption: {
        modules: {
          toolbar: toolbarOptions,
        },
        theme: "snow",
      },
      groupTypesOptions: [
        { id: 'Workshop', label: 'Workshop' },
        { id: 'Health & Wellness', label: 'Health & Wellness' },
        { id: 'Soft Skill', label: 'Soft Skill' }
      ],
      sessionTypeOptions: [
        { id: 'free', label: 'Free' },
        { id: 'paid', label: 'Paid' },
      ],
      sessionNumberOptions: [],
      selected_session_no: 0,
      consultantOptions: [],
      alliancePartners: [],
      clientLists: [],
      assign_data: {
        group_id: null,
        alliance_partner_id: null,
        alliance_client_id: null
      },
      groupView: []
    };
  },
  directives: {
    Ripple,
  },
  setup() {
    const refLogoEl = ref(null);
    const previewLogoEl = ref(null);

    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl);

    const groups = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Group No.", sortable: true },
      { key: "name", label: "Group Name", sortable: true },
      { key: "description", label: "Group Description", sortable: true },
      { key: "actions" },
    ];
    const ucolumns = [
      { key: "index", label: "Client No.", sortable: true },
      { key: "user_name", label: "Client Name", sortable: true },
      { key: "referred_partner", label: "Referred Partner", sortable: true },
      { key: "call", label: "Call", sortable: true },
      { key: "actions", label: "Action", sortable: false },
    ];
    const groupData = ref({
      group_name: "",
      group_type: "",
      name: "",
      description: "",
      session_no: "",
      no_of_participants: "",
      payment_amount: "",
      logo: null,
      session_type: "",
      payment_link: "",
      sessions: {
        start_date: [],
        start_time: [],
        end_time: [],
        duration: [],
        title: [],
        assigned_consultant: [],
        meeting_link: [],        
        room_id: [],  
      }
    });

    const resetGroupData = function () {
      groupData.value = {
        group_name: "",
        group_type: "",
        name: "",
        description: "",
        session_no: "",
        no_of_participants: "",
        payment_amount: "",
        session_type: "",
        payment_link: "",
        logo: "",
        sessions: {
          start_date: [],
          start_time: [],
          end_time: [],
          duration: [],
          title: [],
          assigned_consultant: [],
          meeting_link: [],        
          room_id: [],  
        }
      };
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetGroupData
    );
    return {
      refLogoEl,
      previewLogoEl,
      inputLogoRenderer,
      groups,
      loading,
      sortBy,
      isSortDirDesc,
      ucolumns,
      columns,
      groupData,
      refFormObserver,
      getValidationState,
      resetGroupData,
      resetForm,
    };
  },
  computed: {
    id: function () {
      return this.group.group_id;
    },
  },

  watch: {
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    },
  },
  created() {
    this.getNextGroupId();
    this.getList();
    this.initSessionNumberOptions();
    this.initConsultantsOptions();
  },
  methods: {
    validateSize,
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    async getNextGroupId() {
      const { data } = await axios.get("clientgroup/get_next_group_id");
      this.next_group_id = data.next_group_id;      
    },
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.get("clientGroup", {
          params: this.pagination,
        });
        this.groups = data.groups;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async initConsultantsOptions() {
      const { data } = await axios.get("/consultants/list");
      this.consultantOptions = data.users
      console.log(data);
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    async initSessionNumberOptions () {
      for(let i = 1; i <= 25; i++) {
        this.sessionNumberOptions.push({'id': i, 'label': i});
      }

      console.log(this.sessionNumberOptions)
    },
    handleFilterChange(val) {
      this.pagination.per_page = val;
      this.getList();
    },
    async calculateDuration(i){
      const start = this.groupData.sessions.start_time[i].split(":");
      const end = this.groupData.sessions.end_time[i].split(":");
      var startDate = new Date(0, 0, 0, start[0], start[1], 0);
      var endDate = new Date(0, 0, 0, end[0], end[1], 0);
      var diff = endDate.getTime() - startDate.getTime();
      var hours = Math.floor(diff / 1000 / 60 / 60);
      diff -= hours * 1000 * 60 * 60;
      var minutes = Math.floor(diff / 1000 / 60);
      if (hours < 0)
         hours = hours + 24;

      const duration = (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;   

      this.groupData.sessions.duration[i] = duration 
      this.$refs.sessionDuration[i].$el.value = duration
    },
    async onAssign() {
      try {
        const { data } = await axios.post("/clientgroup/allianassignment", this.assign_data);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assign-clients"].hide();
          this.getList();
        });
      } catch (err) {
        console.log(err);
      }
    },
    async onSubmit() {
      try {
        let data;
        let groupData = new FormData();
        groupData.append("group_name", this.groupData.group_name);
        groupData.append("group_type", this.groupData.group_type);
        groupData.append("name", this.groupData.name);
        groupData.append("description", this.groupData.description);
        groupData.append("session_no", this.groupData.session_no);
        groupData.append("no_of_participants", this.groupData.no_of_participants);
        groupData.append("payment_amount", this.groupData.payment_amount);
        groupData.append("logo", this.logo);
        groupData.append("session_type", this.groupData.session_type);
        groupData.append("sessions", JSON.stringify(this.groupData.sessions));

        if (this.groupData.id) {
          groupData.append("_method", 'put');
          data = await axios.post(`/clientGroup/${this.groupData.id}`, groupData, {
            headers: {
              "content-type": "multipart/form-data",
            },          
          });
        } else {
          data = await axios.post("/clientGroup", groupData, {
            headers: {
              "content-type": "multipart/form-data",
            },                    
          });
        }

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });

        this.resetFormCard()
        this.setSessionNumber();
        this.getList();
        this.logo = null;

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    viewClientsAssignModal(group) {
      this.assign_data.group_id = group.id
      this.assign_data.alliance_partner_id = null
      this.assign_data.alliance_client_id = null
      this.$refs["assign-clients"].show();
    },
    async getAlliancePartners() {
      try {
        const { data } = await axios.get("/alliance-partner/list");
        this.alliancePartners = data.partners
      } catch (err) {
        console.log(err);
      }
    },
    async getClientLists(search) {

        this.clientLists = [];
        this.assign_data.alliance_client_id = null

        const {data} = await axios.get('/clients', {
          params: {
            'for_ap': this.assign_data.alliance_partner_id,
            'search': search,
            'page': 1, 
            'per_page': 200
          },
        });

        data.clients.forEach((value, index) => {
            this.clientLists.push({
              'id': value.id,
              'label': value.name
            })
        });
    },
    async editGroup(group) {
      this.type = 'Edit Group (GP' + group.id + ')';
      this.groupData = group;
      this.setSessionNumber();
      window.scrollTo({ top: 0, behavior: "smooth" });
    },
    async viewGroup(group) {
      this.generatePaymentLink(group.id);
      this.groupView = group;
      this.$refs["view-group-detail"].show();
    },
    viewUserModal(group) {
      this.group = group;
      this.$refs["view-users"].show();
    },

    async handleDeleteGroup(id) {
      try {
        const data = await axios.delete(`/clientGroup/${id}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },

    resetFormCard() {
      this.resetForm();
      this.groupData = {
        group_name: "",
        group_type: "",
        name: "",
        description: "",
        session_no: "",
        no_of_participants: "",
        payment_amount: "",
        logo: null,
        session_type: "",
        payment_link: "",
        sessions: {
          start_date: [],
          start_time: [],
          end_time: [],
          duration: [],
          title: [],
          assigned_consultant: [],
          meeting_link: [],        
          room_id: [],  
        }
      };
      this.groupPackages = [];
      this.type = "Create Group";
      this.readonly = false;
      this.errors = [];
    },
    async setSessionNumber(){
      this.selected_session_no = this.groupData.session_no;
    },
    async getAssignedUsers() {
      try {
        this.loading = true;
        const { data } = await axios.get("clientgroup/assignedusers", {
          params: { group_id: this.group.id },
        });
        this.users = data.users;
      } catch (err) {
        console.log(err);
      }
    },
    async generateVideoLink(i) {
      try {
        const { data } = await axios.post(`/clientgroup/meeting/link`, {
          start_time: this.groupData.sessions.start_time[i],
          consultant_id: this.groupData.sessions.assigned_consultant[i]
        });

        this.groupData.sessions.meeting_link[i] = data.data.meeting_link;
        this.groupData.sessions.room_id[i] = data.data.room_id;
        this.$refs.sessionMeetingLink[i].$el.value = data.data.meeting_link;        

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
            title: "Error generating video link",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async generatePaymentLink(group_id) {
      try {
        const { data } = await axios.post(`/clientgroup/payment/link`, {
          group_id: group_id
        });
        
        this.groupData.payment_link = data.data.payment_link
        this.group_payment_link = data.data.payment_link

      } catch {
        this.groupData.payment_link = ""
        this.group_payment_link = ""
      }
    },
    async handleRemoveUser(id) {
      try {
        const { data } = await axios.delete(`/assignment/${id}/user?type=clientGroups`);
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
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";
#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}

.sessions-box.active {
  border: 1px solid #eeeeee;
  padding: 1.5rem 0.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 0 5px #7777;
  position: relative;
}

.sessions-box:not(.active) .row-action-btn {
  display: none;
}

.sessions-box.active .row-action-btn {
  position: absolute;
  top: -1.2rem;
  right: 1rem;   
}

.sessions-box .session-rows {
  position: relative;
  margin: 1rem 0 2.5rem 0;
  border-bottom: 1px solid #EEEEEE;
  padding-bottom: 1.2rem;
}

.sessions-box .session-rows h3 {
  position: absolute;
  top: -1.2rem;
  font-size: 14px;
  left: 1rem;
}

#view-users .modal-dialog {
  min-width: 1100px;
}

</style>
