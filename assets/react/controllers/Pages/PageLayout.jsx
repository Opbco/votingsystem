import React from "react";
import { useTranslation } from "react-i18next";
import { Outlet, useNavigation } from "react-router-dom";
import { logoutUser, clearErrors } from "./../redux/actions/UserActions";
import ScrollToContent from "./../../utils/ScrollToContent";
import { connect } from "react-redux";
import PreHeader from "../PreHeader";
import Header from "../Header";
import Footer from "../Footer";
import NavBar from "../NavBar";
import BreadCrumbs from "../BreadCrumbs";

const PageLayout = (props) => {
  const { t } = useTranslation();
  const navigation = useNavigation();

  return (
    <>
      <PreHeader />
      <Header />
      <NavBar authenticated={props.authenticated} />
      <main className={navigation.state === "loading" ? "loading" : ""}>
        <ScrollToContent />
        <BreadCrumbs t={t} />
        <div className="container content">
          <Outlet />
        </div>
      </main>
      <Footer />
    </>
  );
};

const mapStateToProps = (state) => ({
  authenticated: state.auth.authenticated,
  user: state.auth.credentials,
});

const mapActionsToProps = {
  logoutUser,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(PageLayout);
