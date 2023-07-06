import React from "react";
import {
  SlSocialTwitter,
  SlSocialInstagram,
  SlSocialFacebook,
} from "react-icons/sl";
import i18n from "i18next";
import { useTranslation } from "react-i18next";

const lngs = [
  { code: "en", nativeName: "English", icon: "/icons/ic_flag_en.svg" },
  { code: "fr", nativeName: "Francais", icon: "/icons/ic_flag_en.svg" },
];

const PreHeader = () => {
  const { t } = useTranslation();

  return (
    <section className="pre-header">
      <ul className="pre-header-links" role="navigation">
        <li>
          <a href="#">
            <SlSocialTwitter size={20} />
          </a>
        </li>
        <li>
          <a href="#">
            <SlSocialInstagram size={20} />
          </a>
        </li>
        <li>
          <a href="#">
            <SlSocialFacebook size={20} />
          </a>
        </li>
        <li>
          <div className="btn-group" role="group">
            <button
              type="button"
              className="btn dropdown-toggle"
              data-bs-toggle="dropdown"
              aria-expanded="false"
              style={{ display: "flex", alignItems: "center" }}
            >
              <img
                src={`/icons/ic_flag_${i18n.language}.svg`}
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="16"
              />
            </button>
            <ul className="dropdown-menu">
              {lngs.map((lng) => {
                return (
                  <li key={lng.code}>
                    <a
                      className="dropdown-item"
                      onClick={() => i18n.changeLanguage(lng.code)}
                    >
                      <img
                        src={`/icons/ic_flag_${lng.code}.svg`}
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="16"
                      />
                    </a>
                  </li>
                );
              })}
            </ul>
          </div>
        </li>
      </ul>
    </section>
  );
};

export default PreHeader;
