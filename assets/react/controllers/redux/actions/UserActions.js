import {
  AUTHENTICATED,
  SET_ERRORS,
  CLEAR_ERRORS,
  UNAUTHENTICATED,
  IS_BUSY,
  SET_USER,
} from "../reducers/UserReducer";

import publicAxios from "../requests/publicAxios";

export const loginUser = (userData, navigate, from) => (dispatch) => {
  dispatch(IS_BUSY());
  publicAxios
    .post("/login_check", userData)
    .then((res) => {
      if (res.data.token) {
        dispatch(AUTHENTICATED(res.data));
        navigate(from, { replace: true });
      }
    })
    .catch((err) => {
      console.log(err.response);
      dispatch(SET_ERRORS(err.message));
    });
};

export const registerUser = (userData, navigate) => (dispatch) => {
  dispatch(IS_BUSY());
  publicAxios
    .post("/register", userData)
    .then((res) => {
      if (res.status === 201) {
        dispatch(
          loginUser(
            { username: userData.username, password: userData.plainPassword },
            navigate,
            "/membre"
          )
        );
      } else {
        dispatch(SET_ERRORS(res.data.error));
      }
    })
    .catch((err) => {
      console.log(err.response);
      dispatch(SET_ERRORS(err.message));
    });
};

export const logoutUser = (navigate) => (dispatch) => {
  dispatch(UNAUTHENTICATED());
  navigate("/login", { replace: true });
};

export const setErrors = (errors) => (dispatch) => {
  dispatch(SET_ERRORS(errors));
};

export const setMe = (membre) => (dispatch) => {
  dispatch(SET_USER(membre));
};

export const clearErrors = () => (dispatch) => {
  dispatch(CLEAR_ERRORS());
};

export const loading = () => (dispatch) => {
  dispatch(IS_BUSY());
};
