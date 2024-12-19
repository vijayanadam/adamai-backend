<template>
  <div class="w-auto h-auto collapse navbar-collapse max-height-vh-100 h-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item" v-if="role === 'admin' || role === 'user'">
        <router-link to="/ViewCallLogs" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">table_view</i>
          Calls Dashboard
        </router-link>
      </li>
     
      <li class="nav-item" v-if="role === 'admin'">
        <router-link to="/companyprofile" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">person</i>
          Company Profile
        </router-link>
      </li>
      <li class="nav-item" v-if="role === 'admin'">
        <router-link to="/users1" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">people</i>
          Users
        </router-link>
      </li>
      <li class="nav-item"  v-if="role === 'admin' || role === 'user'">
        <router-link to="/vbots" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">dashboard</i>
          Phonebots
        </router-link>
      </li>
      <li class="nav-item" v-if="role === 'super'">
        <router-link to="/phonebots" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">dashboard</i>
          Phonebots
        </router-link>
      </li>
     
     
      <li class="nav-item">
        <router-link to="/myprofile" class="nav-link" exact-active-class="active">
          <i class="material-icons-round opacity-10 fs-5">login</i>
          My profile
        </router-link>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" @click.prevent="logout">  <!-- Use <a> to trigger the click -->
          <i class="material-icons-round opacity-10 fs-5">logout</i>
          Logout
        </a>
      </li>

    </ul>
  </div>
</template>

<script>
import userService from "@/services/users.service";
export default {
  name: "SidenavList",
  data() {
    return {
      
      role:'',
    };
  },
  async mounted() {
    await this.fetchRole();
    
  },
  methods: {
    logout() {
      this.$store.dispatch("auth/logout");
    },
    async fetchRole() {
      try {
        const response = await userService.fetchRole();
        this. role = response.role; // Ensure this is the correct structure
      
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },
  }
};
</script>

<style>
.active {
  background-color: #f0f0f0; /* Change this to your desired highlight color */
  color: #000; /* Change this to your desired text color */
}
</style>
