import React from "react";
import { BsWhatsapp, BsTelephoneForward } from "react-icons/bs";
import CONSTANTS from "../utils/Constants";

const MembreCard = ({ membre }) => {
  return (
    <div className="card">
      <img
        src={`${CONSTANTS.BASE_URL}/uploads/${membre.avatar?.fileName}`}
        className="card-img-top"
        alt={membre.name}
      />
      <div className="card-body">
        <div className="header">
          <div className="presentation">
            <h5 className="card-title text-capitalize">
              {membre.name.toLowerCase()}
            </h5>
            <h6 className="card-subtitle mb-2 text-muted">{membre.title}</h6>
          </div>
          <div className="contacts">
            <ul>
              <li>
                <a href={`tel:${membre.phone}`} target="_blank">
                  <BsTelephoneForward />
                </a>
              </li>
              <li>
                <a
                  href={`https://api.whatsapp.com/send?phone=${membre.whatsapp}`}
                  target="_blank"
                >
                  <BsWhatsapp />
                </a>
              </li>
            </ul>
          </div>
        </div>
        <p className="card-text">{membre.description}</p>
      </div>
    </div>
  );
};

export default MembreCard;
