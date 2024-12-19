<template>
  <div class="py-4 container-fluid">
    <div class="mt-4 row">
      <div class="col-12">
        <div class="card">
          <div class="card-header border-bottom">
            <div class="user d-flex align-items-center">
              <div class="col-6">
                <h5 class="mb-0">Phonebots</h5>
              </div>
              <div class="col-6 text-end">
                <router-link to="/addvbot">
                  <material-button class="float-right btn btn-sm btn-success">
                    <i class="fas fa-user-plus me-2"></i> Add Phonebot
                  </material-button>
                </router-link>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table id="datatable-search" class="table table-flush">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Phonebot</th>
                  <th>License/UserKey</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="vbots.length === 0">
                  <td colspan="6" class="text-center">No Phonebots found.</td>
                </tr>
                <tr v-for="vbot in vbots" :key="vbot.id">
                  <td>{{ vbots.indexOf(vbot) + 1 }}</td> <!-- Automatically generated index -->
                  <td>{{ vbot.bezeichnung }}</td>
                  <td>{{ vbot.license_Key }}</td>
                  <td>{{ vbot.status }}</td>
                  <td>{{ formatDate(vbot.created_at) }}</td>
                  <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-info" @click="editItem(vbot.id)">
                      <i class="fas fa-pen"></i>
                    </button>

                    <!-- View Call Logs Button -->
                   
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery';
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import userService from "@/services/users.service";
import Swal from 'sweetalert2';

export default {
  name: 'Vbots',
  data() {
    return {
      vbots: [], // Stores the list of phonebots
      dataTable: null, // DataTable instance
    };
  },

  async mounted() {
    await this.fetchVbots();
  },

  methods: {
    // Fetch the phonebot data
    async fetchVbots() {
      try {
        const response = await userService.getVbots();
        this.vbots = response; // Ensure this is the correct structure
        this.initializeDataTable();
      } catch (error) {
        console.error("Error fetching vbot data:", error);
      }
    },

    // Format the date to a readable format
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },

    // Method to edit a selected phonebot
    editItem(vid) {
      this.$router.push({ name: 'EditVbot', params: { id: vid } });
    },

    // Method to view call logs for a selected phonebot
    viewCallLogs(vid) {
      // Redirect to the call logs page for the selected phonebot
      this.$router.push({ name: 'ViewCallLogs', params: { id: vid } });
    },

    // Method to delete a selected phonebot
    async deleteItem(vid) {
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
      });

      if (result.isConfirmed) {
        try {
          await userService.deleteVbot(vid);
          this.vbots = this.vbots.filter(vbot => vbot.id !== vid);
          this.refreshDataTable();
          Swal.fire('Deleted!', 'Phonebot has been deleted.', 'success');
        } catch (error) {
          console.error(`Error deleting phonebot with ID: ${vid}`, error);
          Swal.fire('Error!', 'There was an error deleting the phonebot. Please try again.', 'error');
        }
      }
    },

    // Initialize DataTable
    initializeDataTable() {
      this.$nextTick(() => {
        if ($.fn.DataTable.isDataTable('#datatable-search')) {
          $('#datatable-search').DataTable().destroy();
        }

        this.dataTable = $('#datatable-search').DataTable({
          data: this.vbots,
          columns: [
            { 
              data: null, // Custom index column
              render: (data, type, row, meta) => {
                return meta.row + 1; // Display index starting from 1
              }
            },
            { data: 'bezeichnung' },
            { data: 'license_Key' },
            { data: 'status' },
            { data: 'created_at' },
            {
              data: null,
              render: (data, type, row) => {
                return `
                  <button class='btn btn-info edit-btn' data-id='${row.id}'>Edit</button>
                  
                `;
              },
            },
          ],
        });

        // Attach event listeners for buttons
        $('#datatable-search tbody').on('click', '.edit-btn', (event) => {
          const vid = $(event.currentTarget).data('id');
          this.editItem(vid);
        });

        

        // After DataTables initialization, apply custom border styles
        this.applyCustomBorders();
      });
    },

    // Refresh the DataTable after an update
    refreshDataTable() {
      if (this.dataTable) {
        this.dataTable.clear().rows.add(this.vbots).draw();
      }
    },

    // Apply custom border styles
    applyCustomBorders() {
      $('#datatable-search th, #datatable-search td').css({
        'border': '1px solid #dcdcdc',
        'padding': '10px',
      });

      // Apply a thicker left border to the first column
      $('#datatable-search td:first-child, #datatable-search th:first-child').css({
        'border-left': '2px solid #dcdcdc',
      });

      $('#datatable-search th').css({
        'border-top': '2px solid #dcdcdc',
      });
    },
  },
};
</script>

<style scoped>
/* Ensure table layout is fixed for better rendering */
#datatable-search {
  table-layout: fixed;
  width: 100%;
}

/* Optional: Style for buttons inside the table cells */
#datatable-search td button {
  margin-right: 5px;
}
</style>
