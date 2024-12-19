<template>
  <div class="py-4 container-fluid">
      <!-- Notification Section -->
      <div @click="viewNewCall(this.route)" v-if="newCallNotification > 0" class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>New Call Alert!</strong> You have {{ newCallNotification }} new call{{ newCallNotification > 1 ? 's' : '' }}. 
  <button type="button" class="btn-close"  aria-label="Close"></button>
</div>

    <div class="mt-4 row">
      <div class="col-12">
        <div class="card">
          <div class="card-header border-bottom">
            <div class="user d-flex align-items-center">
              <div class="col-6">
                <h5 class="mb-0">Calls Dashboard</h5>
              </div>
              <div class="col-6 d-flex justify-content-end">
  <div class="input-group">
    <input
      v-model="searchTerm"
      type="text"
      class="form-control"
      placeholder="Search"
    />
  </div>

  <div class="input-group ml-3">
    <span class="input-group-text"></span>
    <input 
      type="date" 
      v-model="selectedDate" 
      class="form-control datepicker" 
      @change="filterCalls" 
      placeholder="Select Date"
    />
    <button 
      v-if="selectedDate" 
      class="btn btn-outline-secondary" 
      type="button" 
      @click="clearDate" 
      title="Clear Date"
    >
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>



            </div>
          </div>
      
          <div>
  <!-- Dropdown to control number of entries -->
  <label for="pageSizeSelect">Show: </label>
  <select id="pageSizeSelect" v-model="pageSize" @change="onPageSizeChange">
    <option value="5">5</option>
    <option value="10">10</option>
    <option value="20">20</option>
    <option value="50">50</option>
    <option value="All">All</option>
  </select>
  entries
</div>


          <div v-if="loading" class="text-center my-4">
            <i class="fas fa-spinner fa-spin"></i> Loading calls...
          </div>

          <div class="table-responsive" v-if="!loading">
            <table class="table table-striped">
              <thead class="thead-light">
                <tr>
                  <th class="align-middle">
                    <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" />
                  </th>
                  <th class="align-middle">Phonebot</th>
                  <th class="align-middle">
Priority
<button 
  class="btn btn-md btn-link sort-button" 
  @click="toggleSortBy('priority')" 
  :aria-label="sortOrder === 'asc' ? 'Sort in descending order' : 'Sort in ascending order'">
  <i class="fas" :class="sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
</button>
</th>

                  <th class="align-middle">Phone Number</th>
                  <th class="align-middle">Message Preview</th>
                  <th class="align-middle">
Status
<button 
  class="btn btn-md btn-link sort-button" 
  @click="toggleSortBy('status')" 
  :aria-label="sortOrder === 'asc' ? 'Sort in descending order' : 'Sort in ascending order'">
  <i class="fas" :class="sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
</button>
</th>

                  <th class="align-middle">Notes</th>
                  <th class="align-middle">
Date/Time
<!-- Sorting Button -->
<button 
  class="btn btn-md btn-link sort-button" 
  @click="toggleSortBy('created_at')" 
  :aria-label="sortOrder === 'asc' ? 'Sort in descending order' : 'Sort in ascending order'">
  <!-- Icon will toggle based on sortOrder -->
  <i class="fas" :class="sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
</button>
</th>


                  <th class="align-middle">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="filteredCalls.length === 0">
                  <td colspan="9" class="text-center">No calls found.</td>
                </tr>
                <tr v-for="call in paginatedCalls" :key="call.id">
                  <td class="align-middle">
                    <input
                      type="checkbox"
                      :value="call.id"
                      v-model="selectedCalls"
                    />
                  </td>
                  <td class="align-middle">{{ call.bezeichnung }}</td>
                  <td class="align-middle">
                    <button :class="getPriorityClass(call.priority)" class="btn btn-sm btn-info">{{ call.priority }}</button>
                  </td>
                  <td class="align-middle">{{ call.caller_number }}</td>
                  <td class="align-middle">
                    <span class="text-truncate" style="text-decoration: underline;" @click="showFullText(call)">
                      {{ formatText(call.response) }}.....more
                    </span>
                  </td>
                  <td class="align-middle">


<!-- Status Dropdown for each row with a down-arrow icon -->
<div class="dropdown">
  <select
    v-model="call.status"
    @change="updateCallStatus(call)"
    class="form-control"
    id="statusSelect"
  >
    <option value="New">New</option>
    <option value="In Progress">In Progress</option>
    <option value="Completed">Completed</option>
  </select>

  <!-- Dropdown Arrow Icon (Optional) -->
  <span class="dropdown-arrow">
    <i class="fas fa-chevron-down"></i>
  </span>
</div>


</td>

                  <td class="align-middle">
                  <!-- Add Notes Button to open Modal -->
                  <button class="btn btn-sm btn-warning" @click="openNotesModal(call)">
                     Notes
                  </button>
                </td>
                  <td class="align-middle">{{ formatDate(call.created_at) }}</td>
                  <td class="align-middle">
<!-- Mark as Completed button -->
<!-- Button to mark as completed (if status is not 'Completed') -->
<button 
v-if="call.status !== 'Completed'" 
class="btn btn-sm btn-info btn-spacing" 
@click="markAsCompleted(call)" 
title="Mark as Completed">
<i class="fas fa-check"></i> <!-- Icon for "Mark as Completed" -->
</button>

<!-- Completed button (if status is 'Completed') -->
<button 
v-else 
class="btn btn-sm btn-danger btn-spacing" 
title="Completed">
<i class="fas fa-check-circle"></i> <!-- Icon for "Completed" -->
</button>


<!-- Send SMS button 
<button class="btn btn-sm btn-success btn-spacing" @click="openMessageModal(call)">
  Send SMS
</button>--->

<!-- Delete button -->
<button class="btn btn-sm btn-danger" @click="deleteItem(call.id)">
  <i class="fas fa-trash"></i>
</button>
</td>


                </tr>
              </tbody>
            </table>
            <div v-if="selectedCalls.length > 0" class="mt-3">
<button class="btn btn-primary" @click="bulkUpdateStatus">
  Update Status 
</button>
</div>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-4">
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li class="page-item" :class="{'disabled': currentPage === 1}">
                  <button class="page-link" @click="changePage(currentPage - 1)">
                    <i class="fas fa-chevron-left"></i>
                  </button>
                </li>
                <li v-for="page in totalPages" :key="page" class="page-item" :class="{'active': currentPage === page}">
                  <button class="page-link" @click="changePage(page)">{{ page }}</button>
                </li>
                <li class="page-item" :class="{'disabled': currentPage === totalPages}">
                  <button class="page-link" @click="changePage(currentPage + 1)">
                    <i class="fas fa-chevron-right"></i>
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
  
  <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;" aria-hidden="false">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Message Preview</h5>
      <button type="button" class="btn-close" @click="closeModal"></button>
    </div>
    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
      <!-- Single Chatbox for All Messages -->
      <div class="chat-box p-3 border rounded bg-light">
        <!-- Loop through fullText to append messages into a single chatbox -->
        <div v-for="(pair, index) in fullText" :key="index" class="mb-2">
          <span v-if="pair.action === 'UserRecognizedText'" class="d-block">
            <strong>Caller:</strong> {{ pair.text }}
          </span>
          <span v-else-if="pair.action === 'BotResponseText'" class="d-block">
            <strong>Bot:</strong> {{ pair.text }}
          </span>
          <span v-else class="d-block">
            <strong>Action:</strong> {{ pair.text }}
          </span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
    </div>
  </div>
</div>
</div>




<!-- Modal for Notes -->
<div v-if="isModalVisible" class="modal fade show" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notesModalLabel">Notes</h5>
          <button type="button" class="btn-close" aria-label="Close" @click="closeNotesModal"></button>
        </div>
        <div class="modal-body">
          <!-- Display existing notes -->
          <div v-if="callNotes.length > 0">
            <div v-for="(note, index) in callNotes" :key="index" class="note-item">
              <p>{{ note.text }}</p>
             
            </div>
          </div>
          <div v-else>
            <p>No notes available for this call.</p>
          </div>

          <!-- Text area for adding new note -->
          <div class="mt-3">
            <textarea v-model="newNoteText" class="form-control" rows="3" placeholder="Add a new note..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeNotesModal">Close</button>
          <button type="button" class="btn btn-primary" @click="saveNote">Save Note</button>
        </div>
      </div>
    </div>
  </div>
<!--Send Message Modal -->


  <!-- Modal for sending message -->
<!-- Send SMS Modal -->
<div v-if="isMessageModalVisible" class="modal fade show" tabindex="-1" aria-labelledby="sendSmsModalLabel" aria-hidden="true" style="display: block;">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="sendSmsModalLabel">Send SMS to {{ recipient }}</h5>
      <button type="button" class="btn-close" aria-label="Close" @click="closeMessageModal"></button>
    </div>
    <div class="modal-body">
     

      <!-- Editable Message Field -->
      <div class="mt-3">
        <textarea v-model="smsMessage" class="form-control" rows="4" placeholder="Type your message here..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="closeMessageModal">Close</button>
      <button type="button" class="btn btn-primary" @click="sendSms">Send SMS</button>
    </div>
  </div>
</div>
</div>


  
</template>

<script>
import Swal from 'sweetalert2';
import userService from "@/services/users.service"; // Import your API service

export default {
  name: 'Calls',
  data() {
    return {
      calls: [],  // Holds the call data
      selectedCalls: [],  // Holds the selected calls' IDs
      searchTerm: '',  // Holds the search term
      currentPage: 1,  // Tracks the current page
      pageSize: 5,  // Number of items per page
      showModal: false,
      fullText: '',
      loading: false,  // Add a loading state
      pollingInterval: 5000,  // Polling every 5 seconds
      pollingTimer: null,  // Timer reference for polling
      selectAll: false,  // Checkbox to select all rows
      isModalVisible: false,  // Controls visibility of the modal
    selectedCall: null,  // Holds the selected call for the modal
    callNotes: [],  // Stores the notes for the selected call
    newNoteText: '',  // Holds the text of the new note
    sortOrder: 'desc',
    isMessageModalVisible: false,
    recipient: '',
    smsMessage: '',  // The customized SMS message
    newCallNotification:0,
    
    sortBy: 'created_at',
    phoneBotResponse: '',
    selectedDate: '', // Holds the selected date for filtering
    
    
    
  };

    
    
  },

  async mounted() {
    console.log('Component mounted');
    await this.fetchCalls();  // Initial data fetch
    this.startPolling();  // Start polling for new calls
  },

  beforeUnmount() {
    if (this.pollingTimer) {
      clearInterval(this.pollingTimer);
    }
  },

  methods: {
    async fetchCalls() {
      try {
       
        const response = await userService.getCallsById();
        
        this.calls = response.call_logs || [];  // Ensure it's an array
        this.newCallNotification=response.new_calls_count
        
        this.sortCalls();
      } catch (error) {
        console.error("Error fetching calls data:", error);
      } finally {
        this.loading = false;
      }
    },
    toggleSortBy(property) {
if (this.sortBy === property) {
  this.toggleSortOrder(); // Toggle order if the same property is selected
} else {
  this.sortBy = property; // Set the new property to sort by
  this.sortOrder = 'desc'; // Default to descending order when switching criteria
}
this.sortCalls(); // Re-sort after change
},
    viewNewCall() {
      this.newCallNotification=0,
    this.$router.push({ name: 'NewCalls'});
  },
 
    toggleSortOrder() {
  // Toggle between ascending and descending order
  this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
  this.sortCalls(); // Re-sort after the toggle
}, sortCalls() {
this.calls.sort((a, b) => {
  let valueA, valueB;

  if (this.sortBy === 'created_at') {
    // Sort by created_at (date)
    valueA = new Date(a.created_at);
    valueB = new Date(b.created_at);
  } else if (this.sortBy === 'priority') {
    // Sort by priority (you can map it to an index if needed)
    const priorityOrder = ['Normal Callback', 'Important Callback', 'Send Email','Transferred']; // Adjust priority order
    valueA = priorityOrder.indexOf(a.priority);
    valueB = priorityOrder.indexOf(b.priority);
  } else if (this.sortBy === 'status') {
    // Sort by status (assuming it's a string like "New", "In Progress", "Completed")
    const statusOrder = ['New', 'In Progress', 'Completed']; // Adjust status order if needed
    valueA = statusOrder.indexOf(a.status);
    valueB = statusOrder.indexOf(b.status);
  }

  // Perform the sorting based on selected sort order (asc/desc)
  if (this.sortOrder === 'asc') {
    return valueA - valueB;
  } else {
    return valueB - valueA;
  }
});
}
,
    // Update status for a single call
    async updateCallStatus(call) {
try {
  // Show confirmation dialog
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `Are you sure you want to update the status to "${call.status}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, update it!',
  });

  // Proceed if the user confirms
  if (result.isConfirmed) {
    // Make an API call to update the status of the individual call
    await userService.updateCallStatus(call);

    // Show success message after updating
    Swal.fire(
      'Status Updated',
      `The status of the call has been updated to "${call.status}".`,
      'success'
    );
  } else {
    // Optionally show a cancel message
    Swal.fire(
      'Cancelled',
      'The status update has been cancelled.',
      'info'
    );
  }
} catch (error) {
  console.error("Error updating status for call:", error);
  Swal.fire('Error!', 'There was an error updating the status. Please try again.', 'error');
}
}
,
async markAsCompleted(call) {
  try {
    // Update the call status to 'Completed' on the server
    await userService.updateCallStatusCompleted(call);


    // Show success confirmation using SweetAlert2
    Swal.fire({
      title: 'Success!',
      text: 'The call has been marked as completed.',
      icon: 'success',
      confirmButtonText: 'OK'
    });
  } catch (error) {
    // Handle any errors (e.g., API call fails)
    console.error('Error updating call status:', error);

    // Show error confirmation if the update fails
    Swal.fire({
      title: 'Error!',
      text: 'Failed to mark the call as completed. Please try again.',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  }
},
// Bulk update status for selected calls
async bulkUpdateStatus() {
const result = await Swal.fire({
  title: 'Select new status for the selected calls',
  input: 'select',
  inputOptions: {
    'New': 'New',
    'In Progress': 'In Progress',
    'Completed': 'Completed',
  },
  inputPlaceholder: 'Select Status',
  showCancelButton: true,
  confirmButtonText: 'Update Status',
  preConfirm: (newStatus) => {
    // Make sure newStatus is properly initialized here
    if (!newStatus) {
      Swal.showValidationMessage('You must select a status');
      throw new Error('No status selected');
    }
    return newStatus;  // Return newStatus to continue the promise chain
  }
});

if (result.isConfirmed) {
  try {
    // Proceed with the status update logic
    const statusUpdates = this.selectedCalls.map(callId => {
      const call = this.calls.find(c => c.id === callId);
      return { id: call.id, status: result.value };  // Use the selected status from SweetAlert2
    });

    // Make API call to update status
    await userService.updateBulkStatus(statusUpdates);
    
    // Uncheck selected checkboxes by clearing selectedCalls array
    this.selectedCalls = [];

    // Hide the status update button (you can create a flag for this, such as showUpdateButton)
    this.showUpdateButton = false;

    Swal.fire('Updated!', 'The status of the selected calls has been updated.', 'success');
  } catch (error) {
    console.error("Error updating status for selected calls:", error);
    Swal.fire('Error!', 'There was an error updating the status. Please try again.', 'error');
  }
}
}



,
    // Helper methods for selecting all calls
    toggleSelectAll() {
      if (this.selectAll) {
        this.selectedCalls = this.paginatedCalls.map(call => call.id);
      } else {
        this.selectedCalls = [];
      }
    },

    // Highlight priority based on call priority
    getPriorityClass(priority) {
      switch(priority) {
        case 'Normal Callback':
          return 'priority-normal';
        case 'Important Callback':
          return 'priority-important';
        case 'Send Email':
          return 'priority-email';
        case 'Transferred':
          return 'priority-email';  
        default:
          return '';
      }
    },

    // Format the text to show a truncated version
    formatText(response) {
    try {
      const parsedResponse = Array.isArray(response) ? response : JSON.parse(response);
      if (!Array.isArray(parsedResponse) || parsedResponse.length === 0) return '';
      
      const firstText = parsedResponse[0]?.text || '';
      return firstText.length > 20 ? firstText.substring(0, 20) + '...' : firstText;
    } catch (error) {
      console.error('Error parsing response:', error);
      return '';
    }
  },


    // Format the date to a readable format
   formatDate(dateString) {
  // Create a date object from the input string
  const date = new Date(dateString);
  
  // Format the date in the desired format
  const day = date.getDate(); // Get the day of the month
  const month = date.toLocaleString('en-GB', { month: 'short' }); // Get the full month name
  const hour = date.getHours(); // Get the hour
  const minute = date.getMinutes().toString().padStart(2, '0'); // Get minutes, ensuring 2 digits

  // Return the formatted date
  return `${day}. ${month}, ${hour}:${minute}`;
},
    // Delete a call and update the list
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
          console.error("Error deleting call:", error);
          Swal.fire('Error!', 'There was an error deleting the call. Please try again.', 'error');
        }
      }
    },

    // Start polling for new calls
    startPolling() {
      this.pollingTimer = setInterval(async () => {
        await this.fetchCalls();
      }, this.pollingInterval);
    },

    showFullText(call) {
console.log('Type of call.response:', typeof call.response); // Check the response type
console.log('call.response:', call.response); // Log the raw response

if (typeof call.response === 'string') {
  try {
    call.response = JSON.parse(call.response); // Parse the response if it's a JSON string
    console.log('Parsed response:', call.response);
  } catch (error) {
    console.error('Failed to parse response:', error);
    call.response = []; // Default to empty array on parse failure
  }
}

if (Array.isArray(call.response) && call.response.length > 0) {
  this.fullText = call.response; // Assign the parsed response to fullText
} else {
  this.fullText = [{ text: "No valid responses", action: "None" }]; // Fallback content
}

this.showModal = true; // Display the modal
}





,

    closeModal() {
      this.showModal = false;
      this.fullText = ''; // Clear the full text when closing the modal
     
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    
  // Open the modal to add/view notes
  openNotesModal(call) {
    this.selectedCall = call;
    this.newNoteText = ''; // Reset new note text
    this.loadNotesForCall(call.id);
    this.isModalVisible = true;
  },

  // Close the modal
  closeNotesModal() {
    this.isModalVisible = false;
    this.selectedCall = null;
    this.callNotes = [];
  },

  // Fetch notes for the selected call
  async loadNotesForCall(callId) {
    try {
      const response = await userService.getNotesForCall(callId);
      this.callNotes = response || [];
    } catch (error) {
      console.error("Error fetching notes:", error);
    }
  },

  // Save a new note for the selected call
  async saveNote() {
    if (!this.newNoteText.trim()) {
      Swal.fire('Warning', 'Note text cannot be empty.', 'warning');
      return;
    }

    const newNote = {
      text: this.newNoteText,
      created_at: new Date().toISOString(),
    };

    try {
      // Save the note via API
      await userService.addNoteToCall(this.selectedCall.id, newNote);

      // Add note to local state to update the modal immediately
      this.callNotes.push(newNote);
      this.newNoteText = ''; // Reset the input field

      Swal.fire('Success', 'Note added successfully.', 'success');
    } catch (error) {
      console.error("Error saving note:", error);
      Swal.fire('Error!', 'There was an error saving the note. Please try again.', 'error');
    }
  },
  openMessageModal(call) {
    this.currentCallId = call.id;
    this.isMessageModalVisible = true;
    this.recipient = call.caller_number; // Assuming call.caller_number is the phone number
    this.smsMessage = 'Hello, this is a prefilled SMS message. You can edit it before sending.';
  },
  
  // Close the modal
  closeMessageModal() {
    this.isMessageModalVisible = false;
    this.smsMessage = '';  // Clear the message
   
  },

  // Apply the selected template to the message field
  applyTemplate() {
    if (this.selectedTemplate) {
      this.smsMessage = this.selectedTemplate.message;
    }
  },

  // Send SMS
  async sendSms() {
  if (!this.smsMessage.trim()) {
    Swal.fire('Warning', 'Please enter a message!', 'warning');
    return;
  }

  const payload = {
    call_id: this.currentCallId,
    to: this.recipient,  // The recipient phone number
    message: this.smsMessage,  // The message content
  };

  try {
    // Send a POST request to your backend API (which will send SMS via Twilio)
    await userService.sendSms(payload);

    Swal.fire({
      title: 'Success!',
      text: 'Message sending successfully.',
      icon: 'success',
      confirmButtonText: 'OK'
    });
  } catch (error) {
    // Handle any errors (e.g., API call fails)
    console.error('Error updating call status:', error);

    // Show error confirmation if the update fails
    Swal.fire({
      title: 'Error!',
      text: 'Failed to mark the call as completed. Please try again.',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  } finally {
    this.closeMessageModal();  // Close the modal after sending SMS
  }
},

filterCalls() {
  
      this.filteredCalls = this.calls.filter(call => {
        const matchesSearchTerm = call.someField.includes(this.searchTerm); // Adjust the field as necessary
        const matchesDate = this.selectedDate ? new Date(call.created_at).toISOString().split('T')[0] === this.selectedDate : true;
        return matchesSearchTerm && matchesDate;
      });
    },
    clearDate() {
    this.selectedDate = '';  // Or null if you prefer
    this.searchTerm='';
    this.filterCalls();  // Optional: If you want to trigger filtering after clearing the date
  },
  onPageSizeChange() {
    this.currentPage = 1;  // Reset to the first page
  },


  },

  computed: {
    filteredCalls() {
  // Ensure search term is not empty or undefined
  const lowerSearchTerm = this.searchTerm ? this.searchTerm.trim().toLowerCase() : '';
  const selectedDate = this.selectedDate ? this.selectedDate : '';

  console.log('Search Term:', lowerSearchTerm);  // Debugging to check the search term
  console.log('Selected Date:', selectedDate);  // Debugging to check the selected date

  // If no search term and no selected date, return all calls (no filter)
  if (!lowerSearchTerm && !selectedDate) {
    console.log('No search term and no date, returning all calls');
    return this.calls;
  }

  return this.calls.filter(call => {
    // Debugging to check each call's values
    console.log('Checking call:', call);

    // Ensure fields exist before applying the search
    const statusMatch = call.status && call.status.toLowerCase().includes(lowerSearchTerm);
    const priorityMatch = call.priority && call.priority.toLowerCase().includes(lowerSearchTerm);
    const phonebotNameMatch = call.bezeichnung && call.bezeichnung.toLowerCase().includes(lowerSearchTerm); 

    // Date match check
    let dateMatch = true;
    if (selectedDate) {
      // Check if the call's created_at date matches the selected date
      const callDate = new Date(call.created_at).toISOString().split('T')[0];  // Convert call's created_at to date string (YYYY-MM-DD)
      dateMatch = callDate === selectedDate;
    }

    // Debugging the individual matches
    console.log('Status match:', statusMatch);
    console.log('Priority match:', priorityMatch);
    console.log('Phonebot Name match:', phonebotNameMatch);
    console.log('Date match:', dateMatch);

    // Return true if any field matches and the date matches
    return (statusMatch || priorityMatch || phonebotNameMatch) && dateMatch;
  });
},


paginatedCalls() {
  if (this.pageSize === 'All') {
    return this.filteredCalls;  // Show all entries if "All" is selected
  }
  const startIndex = (this.currentPage - 1) * this.pageSize;
  return this.filteredCalls.slice(startIndex, startIndex + parseInt(this.pageSize));
},


totalPages() {
  if (this.pageSize === 'All') {
    return 1;  // Only one page when all entries are displayed
  }
  return Math.ceil(this.filteredCalls.length / this.pageSize);
},

  },
};
</script>

<style scoped>
/* Table styles to ensure borders are applied to every element */
.table {
  width: 100%;
  border-collapse: collapse !important; /* Force collapsing of borders */
  border: 2px solid white !important; /* Ensure border is applied around entire table */
}

.table-striped th,
.table-striped td {
 /* border: 2px solid #dee2e6 !important;  Border for each cell */
  padding: 8px;
  text-align: left;
}

/* Ensuring header background and alignment */
.thead-light {
  background-color: #f8f9fa;
}

/* Table cell alignment */
th, td {
  vertical-align: middle;
}

/* Input checkbox styling */
input[type="checkbox"] {
  margin: 0;
  vertical-align: middle;
}

/* Pagination and search styling */
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

/* Styling for search box */
.input-group {
  width: 100%;
  max-width: 300px;
}

.input-group button {
  background-color: #fff;
  border-left: 1px solid #ced4da;
}

/* Scoped styles for priority highlighting */
.priority-normal {
  background-color:  rgb(194, 194, 32); /* Light gray background */
}

.priority-important {
  background-color: red; /* Yellow background */
}

.priority-email {
  background-color: green; /* Light blue background */
}
.priority-Transferred {
  background-color: green; /* Light blue background */
}
/* Style for dropdown arrow */
.dropdown {
position: relative;
}

.dropdown-arrow {
position: absolute;
top: 50%;
right: 10px;
transform: translateY(-50%);
pointer-events: none; /* Ensures the icon doesn't interfere with user interaction */
font-size: 16px;
color: #6c757d;  /* Adjust color to match your theme */
}
.btn-spacing {
margin-right: 5px; /* Adjust spacing between buttons */
}
/* Basic chat bubble styles */
/* Basic chat bubble styles */
/* Basic chat bubble styles */
.chat-bubble-container {
margin-bottom: 15px;
display: flex;
justify-content: flex-start;  /* Ensures content is aligned to the left */
}

.chat-bubble {
display: flex;
flex-direction: column;  /* Stack text and action vertically */
justify-content: flex-start; /* Ensure content starts from the top */
position: relative;
width: 100%;  /* Set the width to 100% to fill the container */
padding: 12px;
background-color: #e6e6e6;  /* Light gray for user bubbles */
border-radius: 15px;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chat-bubble p {
margin: 0;
word-wrap: break-word;
white-space: pre-line; /* Allow multi-line text */
}

/* Text Bubble (Left Side) */
.chat-text {
width: 80%;  /* Set the text bubble width */
margin-bottom: 5px; /* Add some space between text and action */
}

/* Action Bubble (Right Side) */
.action-bubble {
width: 80%;  /* Set the action bubble width */
padding: 8px;
background-color: #f1f1f1;  /* Light color for the action text */
border-radius: 10px;
font-size: 0.9rem;
max-width: 120px;  /* Adjust as needed */
text-align: center; /* Center the action text */
white-space: nowrap; /* Prevent action text from wrapping */
margin-left: auto;  /* Align the action bubble to the right */
margin-top: 5px;  /* Add space between text and action */
}

/* Modal Content Styling */
.modal-content {
width: 80%; /* Adjust width */
max-width: 500px; /* Set max width */
}

/* Close button in the modal header */
.modal-header .btn-close {
background-color: transparent;
border: none;
}

/* Ensure modal footer buttons are properly styled */
.modal-footer {
text-align: right;
}

/* Add some margin between the search and date picker */
.input-group {
  margin-right: 10px;
}

/* Styling for the date picker */
.input-group.datepicker {
  max-width: 180px;
}

/* Adding some styling to the input fields */
.form-control {
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  font-size: 14px;
  padding: 8px 12px;
}

/* Adding icon inside the date picker */
.input-group-text {
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  border-radius: 5px 0 0 5px;
  color: #6c757d;
  padding: 8px;
}

/* Adding spacing between search bar and date picker */
.ml-3 {
  margin-left: 1rem;
}

/* Optional: Focus styling for better user experience */
.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
}

/* For icon styling inside the date picker (optional) */
.fas.fa-calendar-alt {
  font-size: 16px;
  color: #007bff;
}

.input-group .form-control {
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  font-size: 14px;
  padding: 8px 12px;
}

.input-group .form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
}
.entries-control {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 10px;
}

.entries-control select {
  padding: 5px;
  font-size: 14px;
}



</style>
