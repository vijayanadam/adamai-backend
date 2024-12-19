<template>
  <aside
    id="sidenav-main"
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 ms-3"
    :class="`${
      isRTL ? 'me-3 rotate-caret fixed-end' : 'fixed-start ms-3'
    } ${sidebarType}`"
  >
    <div class="sidenav-header">
      <i
        class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
        aria-hidden="true"
        id="iconSidenav"
      ></i>
      <a class="m-0 navbar-brand" href="/" style="margin-left: 15px !important;">
  <!-- Image with responsive and proper styling -->
  <img
    src="/thumbnail_Adam-Logo.png"
    class="navbar-brand-img"
    alt="main_logo"
    style="max-width: 100px; max-height: 100px; object-fit: contain;"
  />
  <span class="ms-2 font-weight-bold text-white"></span>
</a>

    </div>
    <hr class="horizontal light mt-0 mb-2" />
    <sidenav-list />
  </aside>
</template>
<script>
import SidenavList from "./SidenavList.vue";
import logo from "@/assets/img/logo-ct.png";
import logoDark from "@/assets/img/logo-ct-dark.png";
import { mapState } from "vuex";

export default {
  name: "index",
  components: {
    SidenavList,
  },
  data() {
    return {
      logo,
      logoDark,
    };
  },
  computed: {
    ...mapState(["isRTL", "sidebarType", "isDarkMode"]),
  },
};
</script>
