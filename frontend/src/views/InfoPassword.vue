<template>

  <div class="p-4">
    <Form role="form" :validation-schema="schema" @submit="handleChangePassword">
          <div class="row">
            <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Current Password</label>
            <Field
              type="password"
              class="form-control"
              v-model="user.cpsd"
              name="cpsd"
              id="cpsd"
            />
            <ErrorMessage name="cpsd" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>New Password</label>
            <Field
              type="password"
              class="form-control"
              v-model="user.npsd"
              name="npsd"
              id="npsd"
            />
            <ErrorMessage name="npsd" class="text-danger" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="input-group input-group-static mb-4">
            <label>Confirm Password</label>
            <Field
              type="password"
              class="form-control"
              v-model="user.npsd_confirmation"
              name="npsd_confirmation"
              id="npsd_confirmation"
            />
            <ErrorMessage name="npsd_confirmation" class="text-danger" />
          </div>
        </div>
       
      </div>
  
     
     
  
          <div class="button-row d-flex justify-content-end mt-4">
            <button class="btn btn-sm btn-dark">
            Change Password
            </button>
          </div>
        </Form>
  </div>
    
  </template>
  <script>
  
  import { Form, Field, ErrorMessage } from "vee-validate"; // Import necessary components
  import * as Yup from 'yup';
  import showSwal from "@/mixins/showSwal";
  
  export default {
    name: "InfoPassword",
    components: {
      Form,
      Field,
      ErrorMessage // Register ErrorMessage component
    },
    data() {
      return {
        user: {},
        schema: Yup.object().shape({
          cpsd: Yup.string().required("Current Password is a required input"),
          npsd: Yup.string().required("New Password is a required input").min(8, "Password must have at least 8 characters"),
          npsd_confirmation: Yup.string().required("Confirm password is a required input").oneOf([Yup.ref('npsd')], 'Your passwords do not match.'),
  
  
        }),
      };
    },
    methods: {
      async handleChangePassword() {
        
        try {
         
            await this.$store.dispatch('users/updatePassword', this.user);
              showSwal.methods.showSwal({
              type: "success",
              message: "Password Updated Successfully",
              width: 500,
              timer: 160000, // 1 minute
              showConfirmButton: true
          });
          this.$router.push({ name: "/" });
   
      } catch (error) {
          console.error('Signup error:', error); // Log the error
          showSwal.methods.showSwal({
              type: "error",
              message: "Issue In Current Password!",
              width: 500
          });
      }
    }
    },
   
  };
  </script>
  