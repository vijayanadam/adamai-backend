<template>
  <div class="py-4 container-fluid">
    <div class="row mb-4">
      <div class="col-lg-12 position-relative z-index-2">
        <div class="row">
          
         
          <div class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Total Users', value: userCount }"
              detail="<span class='text-danger text-sm font-weight-bolder'></span>"
              :icon="{
                name: 'person',
                color: 'text-white',
                background: 'success',
              }"
            />
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Total Phonebots', value: vbot }"
              detail="<span class='text-success text-sm font-weight-bolder'></span>"
              :icon="{
                name: 'weekend',
                color: 'text-white',
                background: 'info',
              }"
            />
          </div>
        </div>
  
      </div>
    </div>

    
  </div>
</template>
<script>


import MiniStatisticsCard from "./components/MiniStatisticsCard.vue";

import logoXD from "@/assets/img/small-logos/logo-xd.svg";
import logoAtlassian from "@/assets/img/small-logos/logo-atlassian.svg";
import logoSlack from "@/assets/img/small-logos/logo-slack.svg";
import logoSpotify from "@/assets/img/small-logos/logo-spotify.svg";
import logoJira from "@/assets/img/small-logos/logo-jira.svg";
import logoInvision from "@/assets/img/small-logos/logo-invision.svg";
import team1 from "@/assets/img/team-1.jpg";
import team2 from "@/assets/img/team-2.jpg";
import team3 from "@/assets/img/team-3.jpg";
import team4 from "@/assets/img/team-4.jpg";
import userService from "@/services/users.service";
export default {
  name: "dashboard-default",
  data() {
    return {
      logoXD,
      team1,
      team2,
      team3,
      team4,
      logoAtlassian,
      logoSlack,
      logoSpotify,
      logoJira,
      logoInvision,
      userCount: 0,
      vbot:0,
    };
  },
  async mounted() {
    await this.fetchUserCount();
    
  },
  methods: {
    async fetchUserCount() {
      try {
        const response = await userService.getUserCount();
        this. userCount = response.user_count; // Ensure this is the correct structure
        this. vbot = response.phonebot_count;
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },
  },
  components: {
    
   
    MiniStatisticsCard,
    
  },
};
</script>
