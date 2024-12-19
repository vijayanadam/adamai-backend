<template>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="multisteps-form mb-9">
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-success shadow-success border-radius-lg pt-4 pb-3">
            <p class="ms-3 text-white text-center"><b>UPDATE My PROFILE</b></p>
          

        </div>
        
      </div>
      
                <div class="card-body">
                

                  
<div>
  <div class="button-row d-flex justify-content-end mt-4">
    <router-link to="/changepassword">
                  <material-button class="float-right btn btn-sm btn-success">
                     Change Password
                  </material-button>
                </router-link>

          </div>
  <!-- Check if profileData is available -->
  
  <div class="p-4">
    <Form role="form" :validation-schema="schema" @submit="handleUpdate">
      <div class="row">
       
      <div class="col-md-12">
      <div class="input-group input-group-static mb-4">
        <label>Email</label>
        <Field
          type="text"
          class="form-control"
          v-model="profileData.email"
          name="email"
          id="email"
        />
        <ErrorMessage name="email" class="text-danger" />
      </div>
    </div>
  </div>
  <div class="row">
       
       <div class="col-md-12">
       <div class="input-group input-group-static mb-4">
         <label>Name</label>
         <Field
           type="text"
           class="form-control"
           v-model="profileData.name"
           name="name"
           id="name"
         />
         <ErrorMessage name="name" class="text-danger" />
       </div>
     </div>
   </div>
  

      <div class="button-row d-flex justify-content-end mt-4">
        <button class="btn btn-sm btn-dark">
         Update
        </button>
      </div>
    </Form>
  </div>
</div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import profileService from "@/services/profile.service";
import { Form, Field, ErrorMessage } from "vee-validate"; // Import necessary components
import * as Yup from 'yup';
import showSwal from "@/mixins/showSwal";

export default {
  name: "MyProfile",
  components: {
    Form,
    Field,
    ErrorMessage // Register ErrorMessage component
  },
  data() {
    return {
      profileData: {},
      schema: Yup.object().shape({
       
        email: Yup.string().email("Email has to be a valid email address").required("Email is a required input"),
        name: Yup.string().required("name is a required input"),
        
        

      }),
    };
  },
  methods: {
    async handleUpdate() {
      try {
       console.log(this.profileData)
          await this.$store.dispatch('profile/editMyProfile', this.profileData);
            showSwal.methods.showSwal({
            type: "success",
            message: "Successfully Updated",
            width: 500,
            timer: 160000, // 1 minute
            showConfirmButton: true
        });
 
    } catch (error) {
        console.error('Signup error:', error); // Log the error
        showSwal.methods.showSwal({
            type: "error",
            message: "Email Already Exists!",
            width: 500
        });
    }
  }
  },
  async mounted() {
    try {
      const profile = await profileService.getProfile();
     
      this.profileData = profile;
    } catch (error) {
      console.error("Error fetching profile data:", error);
    }
  }
};
</script>


