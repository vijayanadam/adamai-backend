<template>
  <div class="py-4 container-fluid">
    <div class="mt-4 row">
      <div class="col-12">
        <div class="card">
          <div class="card-header border-bottom">
            <div class="user d-flex align-items-center">
              <div class="col-6">
                <h5 class="mb-0">Calls Dashboard</h5>
              </div>
              <div class="col-6 d-flex justify-content-end">
                <!-- Search Input with Icon on the Right -->
                <div class="input-group">
                  <input
                    v-model="searchTerm"
                    type="text"
                    class="form-control"
                    placeholder="Search"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table id="datatable-search" class="table table-flush">
              <thead class="thead-light">
                <tr>
                  <th>Phonebot</th>
                  <th>Caller</th>
                  <th>Text</th>
                  <th>Action</th>
                  <th>Date/Time</th>
                  <th>Remove</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="filteredCalls.length === 0">
                  <td colspan="6" class="text-center">No calls found.</td>
                </tr>
                <tr v-for="call in paginatedCalls" :key="call.id">
                  <td>{{ call.phonebot_name }}</td>
                  <td>{{ call.caller_number }}</td>
                  <td>
                    <span class="text-truncate" @click="showFullText(call.text)">
                      {{ formatText(call.text) }}
                    </span>
                  </td>
                  <td>{{ call.action }}</td>
                  <td>{{ formatDate(call.created_at) }}</td>
                  <td>
                    <button class="btn btn-sm btn-danger" @click="deleteItem(call.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-4">
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li class="page-item" :class="{'disabled': currentPage === 1}">
                  <button class="page-link" @click="changePage(currentPage - 1)">
                    <i class="fas fa-chevron-left"></i> <!-- Previous icon -->
                  </button>
                </li>
                <li v-for="page in totalPages" :key="page" class="page-item" :class="{'active': currentPage === page}">
                  <button class="page-link" @click="changePage(page)">{{ page }}</button>
                </li>
                <li class="page-item" :class="{'disabled': currentPage === totalPages}">
                  <button class="page-link" @click="changePage(currentPage + 1)">
                    <i class="fas fa-chevron-right"></i> <!-- Next icon -->
                  </button>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for showing full text -->
  <div v-if="showModal" class="modal fade show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          {{ fullText }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import userService from "@/services/users.service"; // Your API service

export default {
  name: 'Calls',
  data() {
    return {
      calls: [],  // Holds the call data
      searchTerm: '',  // Holds the search term
      currentPage: 1,  // Tracks the current page
      pageSize: 5,  // Number of items per page
      showModal: false,
      fullText: '',
      pollingInterval: 5000,  // Polling every 5 seconds
      pollingTimer: null,  // Timer reference for polling
    };
  },

  async mounted() {
    console.log('Component mounted');
    await this.fetchCalls();  // Initial data fetch
    this.startPolling();  // Start polling for new calls
  },

  beforeUnmount() {
    // Clear the polling timer when the component is destroyed
    if (this.pollingTimer) {
      clearInterval(this.pollingTimer);
    }
  },

  methods: {
    // Fetch the calls data from the API
    async fetchCalls() {
      try {
        const response = await userService.getCalls();
        console.log('Fetched Calls:', response.data);
        this.calls = response.data;  // Set the calls data
      } catch (error) {
        console.error("Error fetching calls data:", error);
      }
    },

    // Method to format the text (only first 20 characters + "..." if text is long)
    formatText(text) {
      const safeText = text || '';  // If text is null/undefined, set it to empty string
      return safeText.length > 20 ? safeText.substring(0, 20) + '...' : safeText;
    },

    // Method to format the date into a readable string
    formatDate(dateString) {
      return new Date(dateString).toLocaleString();  // Convert date to a readable format
    },

    // Delete a call
    async deleteItem(cid) {
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
          await userService.deleteCall(cid);  // Call the delete API
          this.calls = this.calls.filter(call => call.id !== cid);  // Remove deleted call from the list
          Swal.fire('Deleted!', 'Call has been deleted.', 'success');
        } catch (error) {
          console.error(`Error deleting call with ID: ${cid}`, error);
          Swal.fire('Error!', 'There was an error deleting the call. Please try again.', 'error');
        }
      }
    },

    // Start polling for new data every 5 seconds
    startPolling() {
      this.pollingTimer = setInterval(() => {
        console.log('Polling for new calls...');
        this.fetchCalls();  // Fetch new data from the API
      }, this.pollingInterval);  // Poll every 5000ms (5 seconds)
    },

    // Method to show full text in a modal when the truncated text is clicked
    showFullText(text) {
      this.fullText = text;
      this.showModal = true;
    },

    // Method to close the modal
    closeModal() {
      this.showModal = false;
      this.fullText = ''; // Clear the full text when closing the modal
    },

    // Change the current page for pagination
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
  },

  computed: {
    // Filter calls based on the search term
    filteredCalls() {
      return this.calls.filter(call => {
        const phonebotName = call.phonebot_name || '';  // Handle undefined/empty phonebot_name
        return phonebotName.toLowerCase().includes(this.searchTerm.toLowerCase());
      });
    },

    // Paginate filtered calls based on the current page and page size
    paginatedCalls() {
      const startIndex = (this.currentPage - 1) * this.pageSize;
      return this.filteredCalls.slice(startIndex, startIndex + this.pageSize);
    },

    // Calculate the total number of pages for pagination
    totalPages() {
      return Math.ceil(this.filteredCalls.length / this.pageSize);
    },
  },
};
</script>

<style scoped>
/* Ensure truncation works */
.text-truncate {
  display: inline-block;
  max-width: 150px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

/* Styling for modal */
.modal {
  display: block;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  max-width: 600px;
  margin: 10% auto;
}

/* Styling for pagination */
.pagination {
  margin: 0;
}

.page-item.disabled .page-link {
  pointer-events: none;
}

.page-link {
  border-radius: 50%;
  padding: 0.5rem;
}

.page-item.active .page-link {
  background-color: #007bff;
  color: white;
}

/* Adjusting the search input and button */
.input-group {
  width: 100%;
  max-width: 300px;
}

.input-group-append {
  display: flex;
}

.input-group button {
  background-color: #fff;
  border-left: 1px solid #ced4da;
}
</style>
