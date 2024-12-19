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
            </div>
          </div>

          <div class="table-responsive">
            <table id="datatable-search" class="table table-flush">
              <thead class="thead-light">
                <tr>
                  <th>#</th> <!-- Index column header -->
                  <th>Phonebot</th>
                  <th>License/UserKey</th>
                  <th>Open AI Key</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="vbots.length === 0">
                  <td colspan="7" class="text-center">No Phonebots found.</td>
                </tr>
                <tr v-for="vbot in vbots" :key="vbot.id">
                  <td>{{ getVbotIndex(vbot) + 1 }}</td> <!-- Automatically generated index -->
                  <td>{{ vbot.bezeichnung }}</td>
                  <td>{{ vbot.license_Key }}</td>
                  <td>{{ vbot.apen_ai_key }}</td>
                  <td>{{ vbot.pstatus }}</td>
                  <td>{{ formatDate(vbot.created_at) }}</td>
                  <td>
                    <button class="btn btn-sm btn-info" @click="editItem(vbot.id)">
                      <i class="fas fa-pen"></i>
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

export default {
  name: 'Phonebots',
  data() {
    return {
      vbots: [],
      dataTable: null,
    };
  },

  async mounted() {
    await this.fetchVbots();
  },

  methods: {
    // Safely get the index of a vbot in the vbots array
    getVbotIndex(vbot) {
      if (Array.isArray(this.vbots)) {
        return this.vbots.indexOf(vbot);
      }
      return -1; // Return -1 if vbots is not an array
    },

    async fetchVbots() {
      try {
        const response = await userService.getPhonebots();
        this.vbots = response.data; // Ensure this is the correct structure
        if (this.dataTable === null) {
          this.initializeDataTable();
        } else {
          this.refreshDataTable();  // If the table exists, refresh it.
        }
      } catch (error) {
        console.error("Error fetching phonebots:", error);
      }
    },

    formatDate(dateString) {
      const date = new Date(dateString);

      // Format the date as YYYY-MM-DD
      const formattedDate = date.toISOString().split('T')[0];

      // Format the time as HH:mm
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      const formattedTime = `${hours}:${minutes}`;

      // Combine date and time with a comma separator
      return `${formattedDate},${formattedTime}`;
    },

    editItem(vid) {
      this.$router.push({ name: 'EditPhonebot', params: { id: vid } });
    },

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
            { data: 'apen_ai_key' },
            { data: 'pstatus' },
            {
              data: 'created_at',
              render: (data) => {
                // Format the created_at field before displaying
                return this.formatDate(data);
              }
            },
            {
              data: null,
              render: (data) => {
                return `
                  <button class='btn btn-info edit-btn' data-id='${data.id}'>Edit</button>
                `;
              },
            },
          ],
          drawCallback: () => {
            this.applyCustomBorders(); // Apply borders after each redraw
          },
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

    refreshDataTable() {
      if (this.dataTable) {
        this.dataTable.clear().rows.add(this.vbots).draw();
      }
    },

    applyCustomBorders() {
      // Apply custom borders to the table body and header cells after DataTable is initialized
      $('#datatable-search th, #datatable-search td').css({
        'border': '1px solid #ddd',  // Light gray border
        'padding': '10px',            // Padding for table cells
      });

      // Apply a thicker left border to the first column
      $('#datatable-search td:first-child, #datatable-search th:first-child').css({
        'border-left': '2px solid #ddd',
      });

      // Optional: Apply a thicker top border for the header row
      $('#datatable-search th').css({
        'border-top': '2px solid #ddd',
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
