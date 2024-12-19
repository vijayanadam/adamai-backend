import axios from 'axios';
import authHeader from './auth-header';
const API_URL = process.env.VUE_APP_API_BASE_URL;


export default {
    

    async addUsers(user) {

        user.type = "users"
        const response = await axios.post(API_URL + "/user", user, { headers: authHeader() });
        return (response.data);
    },

    async getUsers() {
        const response = await axios.get(API_URL + "/user", { headers: authHeader()});
        return (response.data);
    },

    async deleteUser(userId) {
        await axios.delete(API_URL + "/user/" + userId, { headers: authHeader() });
    },
    
    async getUserById(userId) {
        const response = await axios.get(API_URL + "/user/" + userId , { headers: authHeader() });
        return (response.data);
    },
    
    async updateUser(user) {
       
        const response = await axios.patch(API_URL + "/user/" + user.id, user, { headers: authHeader() });
        return (response.data);
    },
    async addVbots(vbots) {
        console.log(vbots)
        const response = await axios.post(API_URL + "/vbot" ,vbots, { headers: authHeader() });
        return (response.data);
    },
    async getVbots() {
        const response = await axios.get(API_URL + "/vbot", { headers: authHeader()});
        return (response.data);
    },
    async deleteVbot(vid) {
        await axios.delete(API_URL + "/vbot/" + vid, { headers: authHeader() });
    },
    async getVbotById(vid) {
        const response = await axios.get(API_URL + "/vbot/" + vid , { headers: authHeader() });
        return (response.data);
    },
    async updateVbot(vbot) {
       
        const response = await axios.patch(API_URL + "/vbot/" + vbot.id, vbot, { headers: authHeader() });
        return (response.data);
    },
    async updatePassword(user) {
       
        const response = await axios.post(API_URL + "/change" ,user, { headers: authHeader() });
        return (response.data);
    },
    async getUserCount() {
        const response = await axios.get(API_URL + "/dashboard", { headers: authHeader()});
        return (response.data);
    },
    async getCalls() {
        const response = await axios.get(API_URL + "/calls", { headers: authHeader()});
        return (response.data);
    },
    async deleteCall(cid) {
        await axios.delete(API_URL + "/calls/" + cid, { headers: authHeader() });
    },
    async getPhonebots() {
        
        const response = await axios.get(API_URL + "/phonebot", { headers: authHeader()});
        return (response.data);
    },
    async updatePhonebot(vbot) {
       
        const response = await axios.patch(API_URL + "/phonebot/" + vbot.id, vbot, { headers: authHeader() });
        return (response.data);
    },
    async fetchRole() {
        const response = await axios.get(API_URL + "/role", { headers: authHeader()});
        return (response.data);
    },
    async getCallsById() {
       
        const response = await axios.get(API_URL + "/callsview", { headers: authHeader()});
        
        return (response.data);
    },
    async updateCallStatus(calls) {
        
       
        const response = await axios.patch(API_URL + "/updateCallStatus/"+calls.id,calls, { headers: authHeader()});

        return (response.data);
    },
    async updateBulkStatus(calls) {
       
        
        // Ensure the data is wrapped correctly for the backend
        const dataToSend = { calls };
    
        // Send the data to the backend
        const response = await axios.patch(API_URL + "/updateBulkStatus", dataToSend, { 
            headers: authHeader() 
        });
        
        return response.data;  // Return the response data from the backend
    },
    async getNotesForCall(cid) {
       
        const response = await axios.get(API_URL + "/notes/"+cid, { headers: authHeader()});
        
        return (response.data);
    },
    async addNoteToCall(id,note) {
        
       
        const response = await axios.post(API_URL + "/notes/"+id,note, { headers: authHeader()});

        return (response.data);
    },
    async getNewCalls() {
       
        const response = await axios.get(API_URL + "/newcalls", { headers: authHeader()});
        
        return (response.data);
    },
    async updateNotificationStatus(calls) {
       
        
        // Ensure the data is wrapped correctly for the backend
        const dataToSend = { calls };
        
        // Send the data to the backend
        const response = await axios.patch(API_URL + "/updateNotificationStatus", dataToSend, { 
            headers: authHeader() 
        });
       
        return response.data;  // Return the response data from the backend
       
    },
    async updateCallStatusCompleted(calls) {
        
       
        const response = await axios.patch(API_URL + "/updateCallStatusCompleted/"+calls.id, { headers: authHeader()});

        return (response.data);
    },
    async sendSms(calls) {
        
       
        const response = await axios.post(API_URL + "/sendSms",calls, { headers: authHeader()});

        return (response.data);
    },
   
}




