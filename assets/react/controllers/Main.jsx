import React from "react";
import Loading from "../utils/Loading";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import Home from "./Pages/Home";
import Contact from "./Pages/Contact";
import ErrorPage from "../utils/ErrorPage";
import PageLayout from "./Pages/PageLayout";
import Login from "./Pages/Login";
import Register from "./Pages/Register";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.min.js";
import "./../utils/i18n";
import { Provider } from "react-redux";
import { persistor, store } from "./redux/store";
import { PersistGate } from "redux-persist/integration/react";
import IsAuthenticated from "../utils/IsAuthenticated";
import Membre from "./Pages/Membre";
import CardPage from "./Pages/CardPage";
import { useTranslation } from "react-i18next";
import { Link } from "react-router-dom";
import ConcoursForm from "./Pages/ConcoursForm";
import FormConcoursPdf, {
  loader as concoursPdfLoader,
} from "./Pages/FormConcoursPdf";

const Main = ({ page }) => {
  const { t } = useTranslation();

  const router = createBrowserRouter([
    {
      path: "/",
      element: <PageLayout />,
      errorElement: <ErrorPage />,
      children: [
        {
          index: true,
          element: <Home />,
          handle: {
            crumb: () => <Link to="/">{t("home")}</Link>,
          },
        },
        {
          path: "login",
          element: (
            <IsAuthenticated auth={false}>
              <Login />
            </IsAuthenticated>
          ),
          handle: {
            crumb: () => <Link to="/login">{t("login")}</Link>,
          },
        },
        {
          path: "register",
          element: (
            <IsAuthenticated auth={false}>
              <Register />
            </IsAuthenticated>
          ),
          handle: {
            crumb: () => <Link to="/register">{t("register")}</Link>,
          },
        },
        {
          path: "concours-form",
          element: (
            <IsAuthenticated auth={false}>
              <ConcoursForm />
            </IsAuthenticated>
          ),
          handle: {
            crumb: () => (
              <Link to="/concours-form">{t("concours.inscription")}</Link>
            ),
          },
        },
        {
          path: "concours-form/:concourscandId",
          element: <FormConcoursPdf />,
          loader: concoursPdfLoader,
          handle: {
            crumb: () => (
              <Link to="/concours-form">{t("concours.inscription")}</Link>
            ),
          },
        },
        {
          path: "membre",
          element: (
            <IsAuthenticated auth={true}>
              <Membre />
            </IsAuthenticated>
          ),
        },
        {
          path: "membre/print",
          element: (
            <IsAuthenticated auth={true}>
              <CardPage />
            </IsAuthenticated>
          ),
        },
        {
          path: "contactus",
          element: <Contact />,
          handle: {
            crumb: () => <Link to="/contactus">{t("contact.title")}</Link>,
          },
        },
      ],
    },
  ]);

  return (
    <React.Suspense fallback={<Loading />}>
      <Provider store={store}>
        <PersistGate loading={<Loading />} persistor={persistor}>
          <RouterProvider router={router} />
        </PersistGate>
      </Provider>
    </React.Suspense>
  );
};

export default Main;
