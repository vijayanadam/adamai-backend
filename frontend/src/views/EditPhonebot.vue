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
              <p class="ms-3 text-white text-center"><b>Activate Phonebot</b></p>
          </div>
        </div>
                  <div class="card-body">
                    <form>
                      <info4 :class="activeClass" />
                    </form>
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
  import Info4 from "./Info4.vue";
  export default {
    name: "EditPhonebot",
    components: {
      Info4,
    },
    data() {
      return {
       
      };
    },
  };
  </script>

  