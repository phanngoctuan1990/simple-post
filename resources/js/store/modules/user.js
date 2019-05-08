const state = {
    authUser: null
}

const mutations = {
    SET_AUTH_USER (state, user) {
        state.authUser = user
    }
}

const actions = {
    setUser: ({commit}, user) => {
        commit('SET_AUTH_USER', user)
    }
}

export default {
    state, mutations, actions
}