import axios from 'axios'
// ------------------------------------
// Constants
// ------------------------------------
export const START_PUZZLE = 'START_PUZZLE'

// ------------------------------------
// Actions
// ------------------------------------

/*  This is a thunk, meaning it is a function that immediately
    returns a function for lazy evaluation. It is incredibly useful for
    creating async actions, especially when combined with redux-thunk! */

export const moveBlock = (moveArray) => {
  return (dispatch, getState) => {
    console.log(getState())
    // axios.get('http://0.0.0.0:8080/start-puzzle');

    return axios.post(
      'http://0.0.0.0:8080/move',
      JSON.stringify({ moveArray }),
      {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })

    .then(function (response) {
      console.log(response);
      return dispatch({
        type    : START_PUZZLE,
        payload : moveArray
      })
    })
    .catch(function (error) {
      console.log(error);
    });
  }
}

export const actions = {
  moveBlock
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [START_PUZZLE] : (state, action) => {
    console.log(action)
    return state
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = [0, 1, 2, 3, 4, 5, 6, 7, 8]
export default function puzzleReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}
