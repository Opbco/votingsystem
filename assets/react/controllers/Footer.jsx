import React from "react";
import { NavLink } from "react-router-dom";
import {
  SlSocialTwitter,
  SlSocialInstagram,
  SlSocialFacebook,
} from "react-icons/sl";
import { useTranslation } from "react-i18next";

const Footer = () => {
  const { t } = useTranslation();

  return (
    <footer>
      <section className="main">
        <div className="about-us">
          <h2>{t("footer.about_church")}</h2>
          <p>{t("footer.words_church")}</p>
        </div>
        <div className="quick_links">
          <h2>{t("footer.quick_links")}</h2>
          <ul className="links">
            <li>
              <NavLink
                to={"/"}
                className={({ isActive, isPending }) =>
                  isActive
                    ? "nav-link active"
                    : isPending
                    ? "nav-link pending"
                    : "nav-link"
                }
              >
                MINESUP
              </NavLink>
            </li>
            <li>
              <NavLink
                to={"/"}
                className={({ isActive, isPending }) =>
                  isActive
                    ? "nav-link active"
                    : isPending
                    ? "nav-link pending"
                    : "nav-link"
                }
              >
                UEb
              </NavLink>
            </li>
            <li>
              <NavLink
                to={"/register"}
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
            <li>
              <NavLink
                to={"/contactus"}
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
        </div>
        <div className="address">
          <h2>{t("footer.our_address")}</h2>
          <p>
            L’institut Supérieur d’Agriculture, du Bois, de l’Eau et de
            l’Environnement (ISABEE), Ebolowa, Sud, Cameroun, Afrique.
          </p>
          <p>
            Phone: +237 263192267 <br /> Email: isabee-ube@isabee.cm
          </p>
        </div>
        <div className="connect-with-us">
          <h2>{t("footer.connect")}</h2>
          <ul>
            <li>
              <SlSocialFacebook />
            </li>
            <li>
              <SlSocialInstagram />
            </li>
            <li>
              <SlSocialTwitter />
            </li>
          </ul>
        </div>
      </section>
      <section className="copyright">
        <h5>Copyright &copy; 2023 {t("footer.all_right_reserved")}</h5>
      </section>
    </footer>
  );
};

export default Footer;
