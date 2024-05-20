<template>
  <b-card>
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">
            <h4>General Details</h4>
            <b-row class="mt-2 mb-2">
              <b-col cols="12" md="4">
                <!-- First Name -->
                <validation-provider #default="validationContext" name="First Name" rules="required">
                  <b-form-group label="First Name" label-for="first_name">
                    <b-form-input id="first_name" v-model="userData.first_name"
                      :state="getValidationState(validationContext)" trim placeholder="John"
                      :class="errors.first_name ? 'is-invalid' : ''" :area-invalid="errors.first_name ? true : false" />
                    <b-form-invalid-feedback v-if="errors.first_name">
                      {{ errors.first_name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- Last Name -->
                <validation-provider #default="validationContext" name="Last Name" rules="required">
                  <b-form-group label="Last Name" label-for="last_name">
                    <b-form-input id="last_name" v-model="userData.last_name"
                      :state="getValidationState(validationContext)" trim placeholder="Doe"
                      :class="errors.last_name ? 'is-invalid' : ''" :area-invalid="errors.last_name ? true : false" />
                    <b-form-invalid-feedback v-if="errors.last_name">
                      {{ errors.last_name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- Email -->
                <validation-provider #default="validationContext" name="Email" rules="required|email">
                  <b-form-group label="Email" label-for="email">
                    <b-form-input id="email" v-model="userData.email" :state="getValidationState(validationContext)" trim
                      :class="errors.email ? 'is-invalid' : ''" :area-invalid="errors.email ? true : false" />
                    <b-form-invalid-feedback v-if="errors.email">
                      {{ errors.email[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- Phone Number -->
                <validation-provider #default="validationContext" name="Phone Number" rules="required">
                  <b-form-group label="Phone Number" label-for="phone number">
                    <b-form-input id="phone_number" v-model="userData.phone" type="number" placeholder="9877766556"
                      :state="getValidationState(validationContext)" :class="errors.phone ? 'is-invalid' : ''"
                      :area-invalid="errors.phone ? true : false" />
                    <b-form-invalid-feedback v-if="errors.phone">
                      {{ errors.phone[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- User Specialization -->
                <validation-provider #default="validationContext" name="Role Type" rules="required">
                  <b-form-group label="Role Type" label-for="role_type" :state="getValidationState(validationContext)">
                    <v-select v-model="userData.specialization" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="specializations" :clearable="false" input-id="role_type" @input="getRoles" />
                    <b-form-invalid-feedback v-if="errors.specialization">
                      {{ errors.specialization[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- User Role -->
                <validation-provider #default="validationContext" name="Sub Role" rules="required">
                  <b-form-group label="Sub Role" label-for="user-role" :state="getValidationState(validationContext)">
                    <v-select v-model="userData.role" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="roles"
                      :reduce="(val) => val.id" input-id="user-role" />
                    <b-form-invalid-feedback v-if="errors.role">
                      {{ errors.role[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- Specialization -->
              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Sub Role" rules="required">
                  <b-form-group label-for="specialized_in" label="Specialization">
                    <b-form-input id="specialized_in" v-model="userData.specialized_in"
                      :state="getValidationState(validationContext)" placeholder="Specialization"
                      :class="errors.specialized_in ? 'is-invalid' : ''"
                      :area-invalid="errors.specialized_in ? true : false" />
                    <b-form-invalid-feedback v-if="errors.specialized_in">
                      {{ errors.specialized_in[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- User Authorization -->
                <validation-provider #default="validationContext" name="Authorization" rules="required">
                  <b-form-group label="Authorization" label-for="authorization"
                    :state="getValidationState(validationContext)">
                    <v-select v-model="userData.authorization" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="authorizations" :clearable="false" input-id="authorization" />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!--/ Specialization -->
              <b-col cols="12" md="4">
                <!-- Percentage Sharing -->
                <validation-provider #default="validationContext" name="Percentage Sharing" rules="required">
                  <b-form-group label=" Percentage Sharing %" label-for="cost_percent">
                    <b-form-input id="cost_percent" v-model="userData.cost_percent" type="number" placeholder="%"
                      :state="getValidationState(validationContext)" :class="errors.cost_percent ? 'is-invalid' : ''"
                      :area-invalid="errors.cost_percent ? true : false" />
                    <b-form-invalid-feedback v-if="errors.cost_percent">
                      {{ errors.cost_percent[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- logo -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.logo ? 'is-invalid' : ''" :area-invalid="errors.logo ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.logo" target="_blank">
                      <b-img ref="previewLogoEl" rounded :src="'/view/file?path=' + userData.logo" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refLogoEl.$el.click()">
                      Logo
                    </b-button>
                    <b-form-file ref="refLogoEl" v-model="logo" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(logo, 'logo', inputLogoRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="logo" @click="
                        $refs.previewLogoEl.src = '/view/file?path=' + userData.logo;
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

              <!-- League consultant -->
              <b-col cols="12" md="2" class="mt-2 mb-2">
                <b-form-group label="League consultant" label-for="league">
                  <b-form-checkbox id="league" v-model="userData.league" name="league" value="1" unchecked-value="0">
                  </b-form-checkbox>
                  <b-form-invalid-feedback v-if="errors.league">
                    {{ errors.league[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <!-- Incharge -->
              <b-col cols="12" md="2" class="mt-2 mb-2">
                <b-form-group label="Incharge" label-for="incharge">
                  <b-form-checkbox id="incharge" v-model="userData.incharge" name="incharge" value="1"
                    unchecked-value="0">
                  </b-form-checkbox>
                  <b-form-invalid-feedback v-if="errors.incharge">
                    {{ errors.incharge[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <h4>Education Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Qualifications -->
              <b-col cols="12" md="4">
                <b-form-group label-for="qualification" label="Qualification">
                  <v-select id="qualification" v-model="userData.qualification"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" label="qualification" :options="qualifications" />
                </b-form-group>
              </b-col>
              <!-- Cost per session -->
              <b-col cols="12" md="4">
                <b-form-group label-for="cost" label="Cost per session">
                  <b-form-input id="cost" v-model="userData.cost" placeholder="Cost per session" />
                </b-form-group>
              </b-col>

              <!-- certificates -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.certificate ? 'is-invalid' : ''"
                  :area-invalid="errors.certificate ? true : false">
                  <b-media-aside>
                    <!-- <a :href="'/view/file?path='+userData.certificates" target="_blank">
                      <b-img
                        ref="previewCertificateEl"
                        rounded
                        :src="'/view/file?path='+userData.certificates"
                        height="80"
                      />
                    </a> -->
                    <ul ref="previewCertificateEl" v-if="!certificate.length" style="list-style: none">
                      <li v-for="(certificate, index) in userData.certificates" :key="certificate">
                        <a class="heading" :href="'/view/file?path=' + certificate" target="_blank">
                          <feather-icon size="20" icon="FileTextIcon"></feather-icon>
                        </a>

                        <feather-icon icon="XIcon" style="cursor: pointer" @click="removeFile(index)"></feather-icon>
                      </li>
                    </ul>
                    <ul ref="previewCertificateEl" v-else>
                      <li v-for="(certificate, index) in certificate" :key="certificate.name">
                        <small>{{ certificate.name }}
                          <feather-icon icon="XIcon" style="cursor: pointer" @click="remove(index)"></feather-icon>
                        </small>
                      </li>
                    </ul>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refCertificateEl.$el.click()">
                      Certificates
                    </b-button>
                    <b-form-file ref="refCertificateEl" v-model="certificate" accept=".jpg, .png, .gif, .pdf"
                      :hidden="true" plain multiple @input="
                        validateSize(certificate, 'certificate', inputCertificateRenderer)
                        " />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="certificate" @click="certificate = []">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF, PNG or PDF. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.certificate">
                  {{ errors.certificate[0] }}
                </b-form-invalid-feedback>
              </b-col>

              <!-- signature -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.signature ? 'is-invalid' : ''"
                  :area-invalid="errors.signature ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.signature_img" target="_blank">
                      <b-img ref="previewSignatureEl" rounded :src="'/view/file?path=' + userData.signature_img"
                        height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refSignatureEl.$el.click()">
                      Signature
                    </b-button>
                    <b-form-file ref="refSignatureEl" v-model="signature" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="
                        validateSize(signature, 'signature', inputSignatureRenderer)
                        " />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="signature" @click="
                        $refs.previewSignatureEl.src =
                        '/view/file?path=' + userData.signature_img;
                      signature = null;
                      ">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.signature">
                  {{ errors.signature[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
            <h4>Bank Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Bank Name -->
              <b-col cols="12" md="4">
                <b-form-group label-for="bank" label="Bank Name">
                  <b-form-input id="bank" v-model="userData.bank_name" placeholder="Bank Name" />
                </b-form-group>
              </b-col>

              <!-- account no -->
              <b-col cols="12" md="4">
                <b-form-group label-for="account_no" label="Account Number">
                  <b-form-input id="account_no" v-model="userData.account_no" placeholder="Account Number" />
                </b-form-group>
              </b-col>

              <!-- IFSC Code -->
              <b-col cols="12" md="4">
                <b-form-group label-for="ifsc" label="IFSC Code">
                  <b-form-input id="ifsc" v-model="userData.ifsc_code" placeholder="IFSC Code" />
                </b-form-group>
              </b-col>

              <!-- cancelled cheque -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.cheque ? 'is-invalid' : ''" :area-invalid="errors.cheque ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.cancelled_cheque" target="_blank">
                      <b-img ref="previewChequeEl" rounded :src="'/view/file?path=' + userData.cancelled_cheque"
                        height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refChequeEl.$el.click()">
                      Cancelled Cheque
                    </b-button>
                    <b-form-file ref="refChequeEl" v-model="cheque" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(cheque, 'cheque', inputChequeRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="cheque" @click="
                        $refs.previewChequeEl.src =
                        '/view/file?path=' + userData.cancelled_cheque;
                      cheque = null;
                      ">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.cheque">
                  {{ errors.cheque[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
            <h4>Other Details</h4>
            <b-row class="mt-2 mb-2">
              <b-col cols="12" md="4">
                <b-form-group label="Date of Birth" label-for="dob">
                  <flat-pickr v-model="userData.dob" class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label="Gender" label-for="gender">
                  <b-form-radio-group v-model="userData.gender" :options="genders" class="demo-inline-spacing"
                    name="gender" id="gender" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="clinic-name" label="Clinic Name">
                  <b-form-input id="clinic-name" v-model="userData.clinic_name" placeholder="Clinic Name" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="website" label="Website">
                  <b-form-input id="website" v-model="userData.website_name" placeholder="Website address" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="address" label="Address">
                  <b-form-textarea id="address" v-model="userData.address" rows="3" placeholder="address" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="city" label="City">
                  <b-form-input id="city" v-model="userData.city" placeholder="city" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="pin" label="Pincode">
                  <b-form-input id="pin" v-model="userData.pincode" placeholder="pin" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="state" label="State">
                  <b-form-input id="state" v-model="userData.state" placeholder="State" />
                </b-form-group>
              </b-col>
              <!-- cancelled idproof -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.idproof ? 'is-invalid' : ''"
                  :area-invalid="errors.idproof ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.idproof" target="_blank">
                      <b-img ref="previewIdproofEl" rounded :src="'/view/file?path=' + userData.idproof" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refIdproofEl.$el.click()">
                      ID Proof
                    </b-button>
                    <b-form-file ref="refIdproofEl" v-model="idproof" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(idproof, 'idproof', inputIdproofRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="idproof" @click="
                        $refs.previewIdproofEl.src =
                        '/view/file?path=' + userData.idproof;
                      idproof = null;
                      ">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.idproof">
                  {{ errors.idproof[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
            <h4>Bio</h4>
            <b-row>
              <!-- bio -->
              <b-col cols="12">
                <validation-provider #default="validationContext" name="Bio" rules="required">
                  <b-form-group label="Bio" label-for="bio-area">
                    <quill-editor id="description" v-model="userData.bio" :options="editorOption" rows="4" />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="qualification" label="Experience">
                  <b-form-input id="ifsc" type="number" step="0.1" :min="0" v-model="userData.experience"
                    placeholder="Years Of Experience" />
                </b-form-group>
              </b-col>
              <!-- <b-col cols="12" md="4">
                <b-form-group label-for="aim" label="Aim">
                  <b-form-textarea id="ifsc" v-model="userData.aim" placeholder="Aim" />
                </b-form-group>
              </b-col> -->
              <!-- <b-col cols="12" md="4">
                <b-form-group label="Skills" label-for="skills">
                  <tags-input
                    v-model="userData.skills"
                    id="tags"
                    id-field="id"
                    text-field="name"
                    :typeahead="true"
                    placeholder=""
                    :add-tags-on-comma="true"
                    :add-tags-on-space="true"
                  >
                  </tags-input>
                </b-form-group>
              </b-col> -->
              <b-col cols="12" md="4">
                <b-form-group label="Languages Spoken" label-for="languages">
                  <tags-input v-model="userData.languages" id="tags" id-field="id" text-field="name" :typeahead="true"
                    :add-tags-on-comma="true" :add-tags-on-space="true" placeholder="">
                  </tags-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="12">
                <b-form-group label="Notes" label-for="languages">
                  <b-form-textarea id="notes" v-model="userData.notes" rows="4" placeholder="notes" />
                </b-form-group>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
        <b-row style="margin-top: 5%; margin-left: 40%">
          <!-- Action Buttons -->
          <router-link :to="{ name: 'consultants' }">
            <b-button variant="outline-secondary" type="reset" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'">
              Back
            </b-button>
          </router-link>

          <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit" v-permission="['create-users']">
            Save Changes
          </b-button>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BButton,
  BMedia,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BFormCheckbox,
  BFormInvalidFeedback,
  BFormFile,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormRadioGroup,
  BFormTextarea,
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";

import { validateSize } from "@core/utils/utils";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";

export default {
  components: {
    BButton,
    BMedia,
    BAvatar,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BTable,
    BCard,
    BCardHeader,
    BCardTitle,
    BFormCheckbox,
    BFormFile,
    vSelect,
    BFormInvalidFeedback,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    flatPickr,
    BFormRadioGroup,
    BFormTextarea,
    "tags-input": VoerroTagsInput,

    ValidationProvider,
    ValidationObserver,
    quillEditor,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
  },
  data() {
    return {
      required,
      email,
      specializations: ["Medical", "Wellness Provider", "Others"],
      authorizations: ["Internal", "External"],
      qualifications: [
        "Graduate",
        "Post Graduate",
        "PG diploma",
        "PhD",
        "Certification Courses",
      ],
      errors: [],
      signature: null,
      logo: null,
      cheque: null,
      idproof: null,
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      maxSize: 2097152,
      roles: [],
    };
  },
  setup(props) {
    const blankUserData = {
      name: "",
      email: "",
      role: null,
      phone: "",
      first_name: "",
      last_name: "",
      specialization: "",
      authorization: "Internal",
      specialized_in: "",
      logo: null,
      signature_img: null,
      certificates: null,
      cancelled_cheque: null,
      idproof: null,
      clinic_name: "",
      website_name: null,
      dob: null,
      gender: null,
      address: null,
      city: "",
      pincode: null,
      state: null,
      cost_percent: null,
      aim: null,
      experience: null,
      skills: [],
      languages: [],
      league: 0,
      incharge: 0,
      bio: null,
    };

    const userData = ref(JSON.parse(JSON.stringify(blankUserData)));
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(blankUserData));
    };

    const refLogoEl = ref(null);
    const previewLogoEl = ref(null);
    const refSignatureEl = ref(null);
    const previewSignatureEl = ref(null);
    const refCertificateEl = ref(null);
    const previewCertificateEl = ref(null);
    const refChequeEl = ref(null);
    const previewChequeEl = ref(null);
    const refIdproofEl = ref(null);
    const previewIdproofEl = ref(null);

    const certificate = ref([]);

    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl);
    const inputSignatureRenderer = useInputImageRenderer(
      refSignatureEl,
      previewSignatureEl
    );
    const inputCertificateRenderer = useInputImageRenderer(
      refCertificateEl,
      previewCertificateEl
    );
    const inputChequeRenderer = useInputImageRenderer(refChequeEl, previewChequeEl);
    const inputIdproofRenderer = useInputImageRenderer(refIdproofEl, previewIdproofEl);

    // const roles = [];

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetuserData
    );

    return {
      // roles,
      certificate,
      userData,
      refFormObserver,
      getValidationState,
      resetForm,

      refLogoEl,
      previewLogoEl,
      refSignatureEl,
      previewSignatureEl,
      inputLogoRenderer,
      inputSignatureRenderer,
      refCertificateEl,
      previewCertificateEl,
      inputCertificateRenderer,
      refChequeEl,
      previewChequeEl,
      inputChequeRenderer,
      refIdproofEl,
      previewIdproofEl,
      inputIdproofRenderer,
    };
  },
  created() {
    this.getRoles();
    if (this.id) {
      this.fetchUserData();
    }
  },

  methods: {
    validateSize,
    async getRoles() {
      try {
        this.roles = [];
        this.userData.role = "";
        const { data } = await axios.get("/roles/list", {
          params: {
            description: this.userData.specialization,
            isconsultant: '1'
          },
        });
        this.roles = data.roles;
      } catch (err) {
        console.log(err);
      }
    },
    setFile(e, prop) {
      let supportedTypes = ["image/jpg", "image/png", "image/jpeg", "image/gif"];
      let image = e.target.files[0];
      let type = image.type;
      if (!supportedTypes.includes(type)) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Please select valid image file",
            icon: "EditIcon",
            variant: "danger",
          },
        });
        return;
      } else {
        this.userData[prop] = e.target.files[0];
      }
    },
    async onSubmit() {
      try {
        this.errors = [];
        if (this.userData.phone) {
          if (this.userData.phone.length < 10 || this.userData.phone.length > 11) {
            this.errors = {
              phone: ["please provide a valid number"],
            };
            return false;
          }
        }
        let data;
        let userData = new FormData();
        userData.append("role", this.userData.role);
        userData.append("name", this.userData.name);
        userData.append("email", this.userData.email);
        userData.append("phone", this.userData.phone);
        userData.append("first_name", this.userData.first_name);
        userData.append("last_name", this.userData.last_name);
        userData.append("qualification", this.userData.qualification);
        userData.append("specialization", this.userData.specialization);
        userData.append("authorization", this.userData.authorization);
        userData.append("specialized_in", this.userData.specialized_in);
        userData.append("logo", this.logo);
        userData.append("cost", this.userData.cost);
        userData.append("cost_percent", this.userData.cost_percent);
        userData.append("signature_img", this.signature);
        this.certificate.map((certificate, index) => {
          userData.append(`certificates[${index}]`, certificate);
        });
        userData.append("cancelled_cheque", this.cheque);
        userData.append("idproof", this.idproof);
        userData.append("league", this.userData.league);
        userData.append("incharge", this.userData.incharge);
        userData.append("bank_name", this.userData.bank_name);
        userData.append("account_no", this.userData.account_no);
        userData.append("ifsc_code", this.userData.ifsc_code);
        userData.append("clinic_name", this.userData.clinic_name);
        userData.append("website_name", this.userData.website_name);
        userData.append("dob", this.userData.dob);
        userData.append("gender", this.userData.gender);
        userData.append("address", this.userData.address);
        userData.append("city", this.userData.city);
        userData.append("pincode", this.userData.pincode);
        userData.append("state", this.userData.state);
        userData.append("experience", this.userData.experience);
        userData.append("aim", this.userData.aim);
        userData.append("skills", JSON.stringify(this.userData.skills));
        userData.append("languages", JSON.stringify(this.userData.languages));
        userData.append("bio", this.userData.bio);
        userData.append("notes", this.userData.notes);
        if (this.id) {
          data = await axios.post(`/consultants/${this.id}`, userData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/consultants", userData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.$router.replace("/consultants/list");
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },

    async fetchUserData() {
      try {
        const { data } = await axios.get(`/consultant/${this.id}`);
        this.userData = data.user;
        this.userData.skills = JSON.parse(this.userData.skills);
        this.userData.languages = JSON.parse(this.userData.languages);
        this.userData.certificates = JSON.parse(this.userData.certificates);
        // if( this.userData.certificates[0]){
        //    this.userData.certificates = this.userData.certificates[0]
        // }else {
        //   this.userData.certificates = null;
        // }
      } catch (err) {
        console.log(err);
      }
    },
    remove(index) {
      this.certificate.splice(index, 1);
    },
    async removeFile(index) {
      try {
        const { data } = await axios.delete(`/account/certificate/${index}`, {
          params: {
            id: this.id,
          },
        });
        if (data.success) {
          this.userData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}

.custom-checkbox .custom-control-label::before,
.custom-checkbox .custom-control-label::after,
.custom-radio .custom-control-label::before,
.custom-radio .custom-control-label::after {
  width: 25px;
  height: 25px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/quill.scss";

.ql-container {
  min-height: 150px !important;
}
</style>
