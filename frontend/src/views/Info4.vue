<template>
 
          
<div class="p-4">
  <Form role="form"  @submit="handleUpdate">
          <div class="row">
            <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Bezeichnung</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.bezeichnung"
              name="bezeichnung"
              id="bezeichnung"
            />
            <ErrorMessage name="bezeichnung" class="text-danger" />
          </div>
        </div>
         
      
            <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Product Name</label>
            <Field as="select" class="form-control" v-model="vbot.product_name" name="product_name" id="product_name">
  <option value="" disabled>Select Product Name</option>
  <option value="KI-Phone-Basic">KI-Phone-Basic</option>
  <option value="KI-Phone-Advanced">KI-Phone-Advanced</option>
  
</Field>
            <ErrorMessage name="product_name" class="text-danger" />
          </div>
        </div>
        </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip benutzername</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_benutzername"
              name="sip_benutzername"
              id="sip_benutzername"
            />
            <ErrorMessage name="sip_benutzername" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip password</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_passwd" 
              name="sip_passwd"
              id="sip_passwd"
            />
            <ErrorMessage name="sip_passwd" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip registername</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_registername"
              name="sip_registername"
              id="sip_registername"
            />
            <ErrorMessage name="sip_registername" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip phonenumber</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_phonenumber" 
              name="sip_phonenumber"
              id="sip_phonenumber"
            />
            <ErrorMessage name="sip_phonenumber" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip server</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_server"
              name="sip_server"
              id="sip_server"
            />
            <ErrorMessage name="sip_server" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sip port</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.sip_port" 
              name="sip_port"
              id="sip_port"
            />
            <ErrorMessage name="sip_port" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Email server</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.e_mail_server"
              name="e_mail_server"
              id="e_mail_server"
            />
            <ErrorMessage name="e_mail_server" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Email user</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.e_mail_user" 
              name="e_mail_user"
              id="e_mail_user"
            />
            <ErrorMessage name="e_mail_user" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Email password</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.e_mail_passwd"
              name="e_mail_passwd"
              id="e_mail_passwd"
            />
            <ErrorMessage name="e_mail_passwd" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>Email server port</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.e_mail_server_port" 
              name="e_mail_server_port"
              id="e_mail_server_port"
            />
            <ErrorMessage name="e_mail_server_port" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Email server from</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.e_mail_server_from"
              name="e_mail_server_from"
              id="e_mail_server_from"
            />
            <ErrorMessage name="e_mail_server_from" class="text-danger" />
          </div>
       
      </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Ansagetext</label>
            <textarea
      class="form-control"
      v-model="vbot.ansagetext"
      name="ansagetext"
      id="ansagetext"
      rows="2"  
    ></textarea>
            <ErrorMessage name="ansagetext" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Prompt</label>
            <textarea
      class="form-control"
      v-model="vbot.prompt"
      name="prompt"
      id="prompt"
      rows="8"  
    ></textarea>
            <ErrorMessage name="prompt" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>chatmodel</label>
            
            <Field as="select" class="form-control" v-model="vbot.chatmodel" name="chatmodel" id="chatmodel">
              <option value="" disabled>Select chatmodel</option>
  <option value="GPT-3.5">GPT-3.5</option>
  <option value="GPT-4o">GPT-4o</option>
  <option value="gpt-4o-mini">gpt-4o-mini</option>
  
  
</Field>
            <ErrorMessage name="chatmodel" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>chatmodel api </label>
            <Field as="select" class="form-control" v-model="vbot.chatmodel_api" name="chatmodel_api" id="chatmodel_api">
              <option value="">Select chatmodel api</option>
  <option value="GPT-3.5" disabled>GPT-3.5</option>
  <option value="GPT-4o">GPT-4o</option>
  <option value="gpt-4o-mini">gpt-4o-mini</option>
  
  
</Field>
            <ErrorMessage name="chatmodel_api " class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sttmodel</label>
            
            <Field as="select" class="form-control" v-model="vbot.sttmodel" name="sttmodel" id="sttmodel">
              <option value="">Select sttmodel</option>
  <option value="OpenAI" disabled>OpenAI</option>
  <option value="Microsoft">Microsoft</option>
  
  
</Field>
            <ErrorMessage name="sttmodel" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>sttmodel api </label>
           
            <Field as="select" class="form-control" v-model="vbot.sttmodel_api" name="sttmodel_api" id="sttmodel_api">
              <option value="">Select sttmodel api</option>
  <option value="OpenAI" disabled>OpenAI</option>
  <option value="Microsoft">Microsoft</option>
  
  
</Field>
            <ErrorMessage name="sttmodel_api " class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>ttsmodel</label>
            <Field as="select" class="form-control" v-model="vbot.ttsmodel" name="ttsmodel" id="ttsmodel">
              <option value="">Select ttsmodel</option>
  <option value="OpenAI" disabled>OpenAI</option>
  <option value="Microsoft">Microsoft</option>
  
  
</Field>
            <ErrorMessage name="ttsmodel" class="text-danger" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>ttsmodel api </label>
            <Field as="select" class="form-control" v-model="vbot.ttsmodel_api" name="ttsmodel_api" id="ttsmodel_api">
              <option value="">Select ttsmodel api</option>
  <option value="OpenAI" disabled>OpenAI</option>
  <option value="Microsoft">Microsoft</option>
  
  
</Field>
            <ErrorMessage name="ttsmodel_api " class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
  <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
            <label>PromptXT-URL</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.prompturl"
              name="prompturl"
              id="prompturl"
            />
            <ErrorMessage name="prompturl" class="text-danger" />
          </div>
  </div>
  <div class="col-md-6">
          <div class="input-group input-group-static mb-4">
            <label>TransportType</label>
            <Field as="select" class="form-control" v-model="vbot.transmit_type" name="transmit_type" id="transmit_type">
  <option value="" disabled>Select TransportType</option>
  <option value="tcp">tcp</option>
  <option value="udp">udp</option>
  <option value="tls">tls</option>
  <option value="stcp">stcp</option>
  
</Field>
            <ErrorMessage name="transmit_type" class="text-danger" />
          </div>
        </div>
 
</div>
<div class="row">
  <div class="col-md-12">
    <div class="input-group input-group-static mb-4">
            <label>STUN Server</label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.stun_server"
              name="stun_server"
              id="stun_server"
            />
            <ErrorMessage name="stun_server" class="text-danger" />
          </div>
  </div>
</div>
      <div class="row">
  <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
      <label class="form-check-label" for="attendedtransfer">Attended Transfer</label>
      <div class="form-check">
        <input
          type="checkbox"
          class="form-check-input"
          v-model="vbot.attendedtransfer"
          @change="logCheckboxValue('attendedtransfer')"
          name="attendedtransfer"
          id="attendedtransfer"
        />
        <label class="form-check-label" for="attendedtransfer"></label>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
      <label class="form-check-label" for="mail_certifikate_validation">Mail Certificate Validation</label>
      <div class="form-check">
        <input
          type="checkbox"
          class="form-check-input"
          v-model="vbot.mail_certifikate_validation"
          @change="logCheckboxValue('mail_certifikate_validation')"
          name="mail_certifikate_validation"
          id="mail_certifikate_validation"
        />
        <label class="form-check-label" for="mail_certifikate_validation"></label>
      </div>
    </div>
  </div>
</div>
<div class="row">
        
        <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Open AI Key </label>
            <Field
              type="text"
              class="form-control"
              v-model="vbot.apen_ai_key" 
              name="apen_ai_key"
              id="apen_ai_key"
            />
            <ErrorMessage name="apen_ai_key " class="text-danger" />
          </div>
        </div>
      </div>
<Field
            type="hidden"
            class="form-control"
            v-model="vbot.id"
            name="id"
            id="id"
          />

          <div class="button-row d-flex justify-content-end mt-4">
            <button class="btn btn-sm btn-dark">
             Update Phonebot
            </button>
          </div>
         
        </Form>
       
</div>
  
</template>
<script>
import userService from "@/services/users.service";
import { Form, Field, ErrorMessage } from "vee-validate"; // Import necessary components

import showSwal from "@/mixins/showSwal";

export default {
  name: "Info4",
  
  components: {
    Form,
    Field,
    ErrorMessage // Register ErrorMessage component
  },
  data() {
    return {
      vbot: {
        mail_certifikate_validation: false,
        attendedtransfer: false,
        // Initialize other properties as needed
      },
    };
  },
  methods: {
    logCheckboxValue(field) {
      console.log(`${field} value:`, this.vbot[field]);
    },
    async handleUpdate() {
      
      try {
       
          await this.$store.dispatch('users/updatePhonebot',this.vbot);
            showSwal.methods.showSwal({
            type: "success",
            message: "Phonebot Updated Successfully",
            width: 500,
            timer: 160000, // 1 minute
            showConfirmButton: true
        });
        this.$router.push({ name: 'phonebots' })
 
    } catch (error) {
        console.error('Phonebot error:', error); // Log the error
        showSwal.methods.showSwal({
            type: "error",
            message: "Error In Updating Phonebot!",
            width: 500
        });
    }
  },
  
  },
  async mounted() {
  const vid = this.$route.params.id;

  try {
    const vbots = await userService.getVbotById(vid);
    console.log('Fetched vbot data:', vbots); // Check the raw data

   

    this.vbot = {
      ...vbots,
      mail_certifikate_validation: vbots.mail_certifikate_validation === '1' || vbots.mail_certifikate_validation === 1,
      attendedtransfer: vbots.attendedtransfer === '1' || vbots.attendedtransfer === 1,
    };

    // Alert the value to check
 
    console.log('vbot after setting values:', this.vbot);
  } catch (error) {
    console.error("Error fetching profile data:", error);
  }
}


    
 
};
</script>
