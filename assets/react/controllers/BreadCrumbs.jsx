import React from "react";
import { useMatches, useLocation, Link } from "react-router-dom";

const BreadCrumbs = ({ t }) => {
  let location = useLocation();
  let matches = useMatches();
  const pathname = location.pathname;
  let crumbs = matches
    // first get rid of any matches that don't have handle and crumb
    .filter((match) => Boolean(match.handle?.crumb))
    // now map them into an array of elements, passing the loader
    // data to each one
    .map((match) => match.handle.crumb(match.data));

  console.log(pathname);

  return (
    <nav aria-label="breadcrumb" className="breadcrumb-container">
      <ol className="breadcrumb">
        {pathname != "/" && (
          <li className="breadcrumb-item" key={"HomeStart"}>
            <Link to="/">{t("home")}</Link>
          </li>
        )}
        {crumbs.map((crumb, index) => (
          <li className="breadcrumb-item" key={index}>
            {crumb}
          </li>
        ))}
      </ol>
    </nav>
  );
};

export default BreadCrumbs;
