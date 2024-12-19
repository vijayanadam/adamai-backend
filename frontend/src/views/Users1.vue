<template>
  <div class="py-4 container-fluid">
    <div class="mt-4 row">
      <div class="col-12">
        <div class="card">
          <div class="card-header border-bottom">
            <div class="user d-flex align-items-center">
              <div class="col-6">
                <h5 class="mb-0">Users</h5>
              </div>
              <div class="col-6 text-end">
                <router-link to="/addusers">
                  <material-button class="float-right btn btn-sm btn-success">
                    <i class="fas fa-user-plus me-2"></i> Add User
                  </material-button>
                </router-link>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table id="datatable-search" class="table table-flush">
              <thead class="thead-light">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="users.length === 0">
                  <td colspan="5" class="text-center">No users found.</td>
                </tr>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td>{{ formatDate(user.created_at) }}</td>
                  <td>
                    <button class="btn btn-sm btn-info" @click="editItem(user.id)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" @click="deleteItem(user.id)">
                      <i class="fas fa-trash"></i>
                    </button>
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
  name: 'Users1',
  data() {
    return {
      users: [],
      dataTable: null,
    };
  },

  async mounted() {
    await this.fetchUsers();
  },

  methods: {
    async fetchUsers() {
      try {
        const response = await userService.getUsers();
        this.users = response; // Ensure this is the correct structure
        this.initializeDataTable();
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },

    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },

    editItem(userId) {
      this.$router.push({ name: 'EditUser', params: { id: userId } });
    },

    async deleteItem(userId) {
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
          await userService.deleteUser(userId);
          this.users = this.users.filter(user => user.id !== userId);
          this.refreshDataTable();
          Swal.fire('Deleted!', 'User has been deleted.', 'success');
        } catch (error) {
          console.error(`Error deleting user with ID: ${userId}`, error);
          Swal.fire('Error!', 'There was an error deleting the user. Please try again.', 'error');
        }
      }
    },

    initializeDataTable() {
      this.$nextTick(() => {
        if ($.fn.DataTable.isDataTable('#datatable-search')) {
          $('#datatable-search').DataTable().destroy();
        }

        this.dataTable = $('#datatable-search').DataTable({
          data: this.users,
          paging: false,  // Disable pagination
          searching: false,  // Disable search
          info: false,  // Disable info (e.g., "Showing 1 to 10 of 50 entries")
          columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'created_at' },
            {
              data: null,
              render: (data, type, row) => {
                return `  
                  <button class='btn btn-info edit-btn' data-id='${row.id}'>Update</button>
                  <button class='btn btn-danger delete-btn' data-id='${row.id}'>Delete</button>
                `;
              },
            },
          ],
        });

        // Attach event listeners for buttons
        $('#datatable-search tbody').on('click', '.edit-btn', (event) => {
          const userId = $(event.currentTarget).data('id');
          this.editItem(userId);
        });

        $('#datatable-search tbody').on('click', '.delete-btn', (event) => {
          const userId = $(event.currentTarget).data('id');
          this.deleteItem(userId);
        });

        // After DataTables initialization, apply custom border styles
        this.applyCustomBorders();
      });
    },

    refreshDataTable() {
      if (this.dataTable) {
        this.dataTable.clear().rows.add(this.users).draw();
      }
    },

    applyCustomBorders() {
      // Apply custom borders to the table body and header cells after DataTable is initialized
      $('#datatable-search th, #datatable-search td').css({
        'border': '1px solid #dcdcdc',  // Light gray border
        'padding': '10px',              // Ensure padding is applied
      });

      // Apply a thicker left border to the first column
      $('#datatable-search td:first-child, #datatable-search th:first-child').css({
        'border-left': '2px solid #dcdcdc',
      });

      // Optional: Apply a thicker top border for the header row
      $('#datatable-search th').css({
        'border-top': '2px solid #dcdcdc',
      });
    },
  },
};
</script>

<style scoped>
/* Force a fixed table layout for better rendering */
#datatable-search {
  table-layout: fixed;
  width: 100%;
}

/* Optional: Style for buttons inside the table cells */
#datatable-search td button {
  margin-right: 5px;
}
</style>
