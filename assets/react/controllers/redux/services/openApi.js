import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import CONSTANTS from "../../../utils/Constants";

export const openApi = createApi({
  reducerPath: "openApi",
  baseQuery: fetchBaseQuery({
    baseUrl: CONSTANTS.BASE_API_URL,
    prepareHeaders: (headers, { getState }) => {
      console.log("states", getState);
      headers.set("Content-Type", "application/json");
      return headers;
    },
  }),
  endpoints: (builder) => ({
    getFeaturedMembres: builder.query({
      query: () => "/open/personnels?featured=1",
    }),
    getMyCurrentStructures: builder.query({
      query: (personnelId) => `/open/personnels/${personnelId}/structures`,
    }),
    getAllMyStructures: builder.query({
      query: (personnelId) =>
        `/open/personnels/${personnelId}/structures?all=1`,
    }),
  }),
});

export const {
  useGetFeaturedMembres,
  useGetMyCurrentStructures,
  useGetAllMyStructures,
} = openApi;
