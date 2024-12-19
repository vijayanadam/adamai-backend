/* eslint-disable no-unused-vars */
import usersService from '../services/users.service';

const initialState = { users: null, user: null};

export const users = {
    namespaced: true,
    state: initialState,
    actions: {
        

        async addUsers({ commit }, newItem) {
            const item =  await usersService.addUsers(newItem);
            commit('getItemSuccess', item);
        },

        async updateUser({ commit }, newItem) {
            const item =  await usersService.updateUser(newItem);
            commit('getItemSuccess', item);
        },
        async addVbots({ commit }, newItem) {
            const item =  await usersService.addVbots(newItem);
            commit('getItemSuccess', item);
        },

        async updateVbot({ commit }, newItem) {
            const item =  await usersService.updateVbot(newItem);
            commit('getItemSuccess', item);
        },
        async updatePassword({ commit }, newItem) {
            const item =  await usersService.updatePassword(newItem);
            commit('getItemSuccess', item);
        },
        async updatePhonebot({ commit }, newItem) {
            const item =  await usersService.updatePhonebot(newItem);
            commit('getItemSuccess', item);
        },
        async updateCallStatus({ commit }, newItem) {
            const item =  await usersService.updateCallStatus(newItem);
            commit('getItemSuccess', item);
        },
        async updateBulkStatus({ commit }, newItem) {
            const item =  await usersService.updateBulkStatus(newItem);
            commit('getItemSuccess', item);
        },
        async updateCallStatusCompleted({ commit }, newItem) {
            const item =  await usersService.updateCallStatusCompleted(newItem);
            commit('getItemSuccess', item);
        },
        async sendSms({ commit }, newItem) {
            const item =  await usersService.sendSms(newItem);
            commit('getItemSuccess', item);
        },

    },
    mutations: {
        getItemsSuccess(state, items) {
            state.items = items;
        },
        getItemSuccess(state, item) {
            state.item = item;
        },
        
    },
   
}

