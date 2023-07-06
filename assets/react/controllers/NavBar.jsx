import React from "react";
import { useTranslation } from "react-i18next";
import { NavLink } from "react-router-dom";

const NavBar = (props) => {
  const { t } = useTranslation();
  return (
    <nav
      className="navbar navbar-expand-md navbar-dark sticky-top"
      style={{ backgroundColor: "var(--main-clr)" }}
    >
      <div className="container-fluid">
        <button
          className="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarTogglerDemo02"
          aria-controls="navbarTogglerDemo02"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarTogglerDemo02">
          <ul className="navbar-nav me-auto mb-2 mb-lg-0">
            <li className="nav-item">
              <NavLink
                to={`/`}
                aria-current="page"
                className={({ isActive, isPending }) =>
                  isActive
                    ? "nav-link active"
                    : isPending
                    ? "nav-link pending"
                    : "nav-link"
                }
              >
                {t("home")}
              </NavLink>
            </li>
            {props.authenticated && (
              <li className="nav-item">
                <NavLink
                  to={`/membre`}
                  className={({ isActive, isPending }) =>
                    isActive
                      ? "nav-link active"
                      : isPending
                      ? "nav-link pending"
                      : "nav-link"
                  }
                >
                  {t("membre")}
                </NavLink>
              </li>
            )}
            <li className="nav-item dropdown">
              <a
                className="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                {t("departement.label")}
              </a>
              <ul className="dropdown-menu">
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.fsba")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.aesh")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.erpe")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.esva")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.fstb")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.gh")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.gr")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.hste")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.mc")}
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("departement.se")}
                  </a>
                </li>
              </ul>
            </li>
            <li className="nav-item dropdown">
              <a
                className="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                {t("concours.label")}
              </a>
              <ul className="dropdown-menu">
                <li>
                  <a className="dropdown-item" href="#">
                    {t("concours.admission")}
                  </a>
                </li>
                <li>
                  <NavLink
                    to={`/`}
                    className={({ isActive, isPending }) =>
                      isActive
                        ? "dropdown-item active"
                        : isPending
                        ? "dropdown-item pending"
                        : "dropdown-item"
                    }
                  >
                    {t("concours.communique")}
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink
                    to={`/concours-form`}
                    className={({ isActive, isPending }) =>
                      isActive
                        ? "dropdown-item active"
                        : isPending
                        ? "dropdown-item pending"
                        : "dropdown-item"
                    }
                  >
                    {t("concours.inscription")}
                  </NavLink>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    {t("concours.results")}
                  </a>
                </li>
              </ul>
            </li>
            <li className="nav-item">
              <NavLink
                to={`/contactus`}
                className={({ isActive, isPending }) =>
                  isActive
                    ? "nav-link active"
                    : isPending
                    ? "nav-link pending"
                    : "nav-link"
                }
              >
                {t("contact.title")}
              </NavLink>
            </li>
          </ul>
          <ul className="navbar-nav">
            {!props.authenticated && (
              <>
                <li className="nav-item">
                  <NavLink
                    to={`/login`}
                    className={({ isActive, isPending }) =>
                      isActive
                        ? "nav-link active"
                        : isPending
                        ? "nav-link pending"
                        : "nav-link"
                    }
                  >
                    {t("login")}
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink
                    to={`/register`}
                    className={({ isActive, isPending }) =>
                      isActive
                        ? "nav-link active"
                        : isPending
                        ? "nav-link pending"
                        : "nav-link"
                    }
                  >
                    {t("register")}
                  </NavLink>
                </li>
              </>
            )}
            {props.authenticated && (
              <li className="nav-item dropdown">
                <a
                  className="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <BiUserPin style={{ marginInlineEnd: 2 }} /> {t("profile")}
                </a>
                <ul className="dropdown-menu">
                  <li>
                    <a className="dropdown-item" href="#">
                      {t("user.profile")}
                    </a>
                  </li>
                  <li>
                    <a className="dropdown-item" href="#">
                      {t("user.update_password")}
                    </a>
                  </li>
                  <li>
                    <hr className="dropdown-divider" />
                  </li>
                  <li>
                    <a
                      className="dropdown-item"
                      href="#"
                      onClick={() => props.logoutUser()}
                    >
                      {t("logout")}
                    </a>
                  </li>
                </ul>
              </li>
            )}
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default NavBar;
