import React from "react";
import { useRouteError, useNavigate } from "react-router-dom";

export default function ErrorPage() {
  const error = useRouteError();
  const navigate = useNavigate();

  return (
    <div id="error-page" className="container text-center">
      <div className="row">
        <div className="col d-flex flex-column justify-content-center">
          <h1>Oops!</h1>
          <p>Sorry, an unexpected error has occurred.</p>
          <p>
            <i>{error.statusText || error.message}</i>
          </p>
          <p>
            <a href="#" className="btn btn-link" onClick={() => navigate(-1)}>
              Go back
            </a>
          </p>
        </div>
      </div>
    </div>
  );
}
