import React, { forwardRef } from "react";
import { format } from "date-fns";
import QRCode from "react-qr-code";
import Label from "./Label";

const CardVerso = forwardRef(({ personnel, fonctions }, ref) => {
  return (
    <div ref={ref} className="cardM">
      <div className="card-verso">
        <div
          style={{
            height: "auto",
            margin: "0 auto",
            maxWidth: 140,
            width: "100%",
          }}
        >
          <QRCode
            size={560}
            level="L"
            style={{
              height: "auto",
              maxWidth: "100%",
              width: "100%",
              border: "3px solid #fff",
              borderRadius: 4,
            }}
            value={`${personnel.id}-${personnel.name}`}
            viewBox={`0 0 560 560`}
          />
        </div>
        <div className="card-fonctions">
          {fonctions.map((value, i) => (
            <div className="card-position" key={`strucfonction${i}`}>
              <h3>
                {`${value.structure.subDivision.name}`},&nbsp;&nbsp;
                {`${value.structure.name}`}&nbsp;&nbsp;
              </h3>
              <h3>
                {`${value.fonction.name}`}&nbsp;&nbsp;
                <Label trans="Since on"> Depuis le </Label>&nbsp;&nbsp;
                <em>
                  {format(new Date(value.dateAffectation), "dd MMM yyyy")}
                </em>
              </h3>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
});

export default CardVerso;
