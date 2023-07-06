import React, { forwardRef } from "react";
import Label from "./Label";
import { format } from "date-fns";

const CardRecto = forwardRef(({ personnel, fonctions }, docRef) => {
  return (
    <div ref={docRef} className="cardM">
      <div className="card-content-header">
        <div className="card-logo">
          <img src="/images/logo_epc.png" alt="logo" />
        </div>
        <div className="card-title">
          <h1>CARTE DE MEMBRES|PASTEURS</h1>
          <h4>PERSONNEL ID CARD</h4>
        </div>
      </div>
      <div className="card-main">
        <div className="card-content">
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Full name">Nom complet </Label>
              <h3>{personnel.name}</h3>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Gender">Genre </Label>
              <span>{personnel.gender}</span>
            </div>
            <div className="card-item">
              <Label trans="Marital status">Statut</Label>
              <span>{personnel.maritalStatus}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Born on">Né le </Label>
              <span>{format(new Date(personnel.dob), "dd MMM yyyy")}</span>
            </div>
            <div className="card-item">
              <Label trans="At">à </Label>
              <span>{personnel.pob}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Father">Père </Label>
              <span>{personnel.fatherName}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Mother">Mère </Label>
              <span>{personnel.motherName}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Consecration date">Date consécration </Label>
              <span>
                {format(new Date(personnel.dateConsecration), "dd MMM yyyy")}
              </span>
            </div>
            <div className="card-item">
              <Label trans="At">à </Label>
              <span>{personnel.paroisseConsecration?.name}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Phone number">Téléphone </Label>
              <span>{personnel.phone}</span>
            </div>
          </div>
          <div className="card-content-row">
            <div className="card-item">
              <Label trans="Address">Adresse </Label>
              <span>{personnel.address}</span>
            </div>
          </div>
        </div>
        <div className="card-avatar">
          <div className="card-avatar-img">
            <img src={`/uploads/${personnel.avatar?.fileName}`} alt="Picture" />
          </div>
          <div className="card-avatar-content">
            <h3 className="roles">{personnel.title}</h3>
          </div>
        </div>
      </div>
    </div>
  );
});

export default CardRecto;
