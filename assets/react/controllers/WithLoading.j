import { useState, useEffect } from 'react';

function withLoading(WrappedComponent) {
  return function LoadingComponent({ isLoading, ...props }) {
    const [loading, setLoading] = useState(isLoading);

    useEffect(() => {
      setLoading(isLoading);
    }, [isLoading]);

    if (loading) {
      return <div>Loading...</div>;
    }

    return <WrappedComponent {...props} />;
  };
}

const TodoListWithLoading = withLoading(TodoList);


function Toggle({ children }) {
    const [isVisible, setIsVisible] = useState(false);
  
    function handleClick() {
      setIsVisible(!isVisible);
    }
  
    return children({
      isVisible,
      toggle: handleClick
    });
  }


  function App() {
    return (
      <Toggle>
        {({ isVisible, toggle }) => (
          <>
            <button onClick={toggle}>Toggle</button>
            {isVisible && <p>This content is visible</p>}
          </>
         )}
        </Toggle>
    );
  }