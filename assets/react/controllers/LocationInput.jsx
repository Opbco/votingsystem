import React, { useState } from "react";
import PlacesAutocomplete, {
  geocodeByAddress,
  geocodeByPlaceId,
  getLatLng,
} from "react-places-autocomplete";
import { useTranslation } from "react-i18next";

const LocationInput = (props) => {
  const { t } = useTranslation();
  const [adresse, setAdresse] = useState(() => props.address);

  const handleChange = (address) => {
    setAdresse(address);
  };

  const handleSelect = (address) => {
    geocodeByAddress(address)
      .then((results) => getLatLng(results[0]))
      .then((latLng) => {
        props.setAddress((prev) => ({
          ...prev,
          address,
          latitude: latLng.lat,
          longitude: latLng.lng,
        }));
      })
      .catch((error) => console.error("Error", error));
  };

  return (
    <PlacesAutocomplete
      value={adresse}
      onChange={handleChange}
      onSelect={handleSelect}
    >
      {({ getInputProps, suggestions, getSuggestionItemProps, loading }) => (
        <div className="mb-3">
          <label htmlFor="InputAddress" className="form-label">
            {t("address")}
          </label>
          <input
            {...getInputProps({
              id: "InputAddress",
              name: "address",
              className: "form-control location-search-input",
              placeholder: "Search address ...",
            })}
          />
          <div className="autocomplete-dropdown-container">
            {loading && <div>Loading...</div>}
            {suggestions.map((suggestion) => {
              const className = suggestion.active
                ? "suggestion-item--active"
                : "suggestion-item";
              // inline style for demonstration purpose
              const style = suggestion.active
                ? { backgroundColor: "#fafafa", cursor: "pointer" }
                : { backgroundColor: "#ffffff", cursor: "pointer" };
              return (
                <div
                  {...getSuggestionItemProps(suggestion, {
                    className,
                    style,
                  })}
                >
                  <span>{suggestion.description}</span>
                </div>
              );
            })}
          </div>
        </div>
      )}
    </PlacesAutocomplete>
  );
};

export default LocationInput;
