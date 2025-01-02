function showAlert(message, type = 'error') {
    console.log('Alert triggered:', message, type);
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.zIndex = '9999';
    
    // Add initial styles for the alert
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.padding = '15px 25px';
    alertDiv.style.borderRadius = '4px';
    alertDiv.style.color = 'white';
    alertDiv.style.backgroundColor = type === 'success' ? '#4CAF50' : 
                                   type === 'error' ? '#f44336' : 
                                   '#ff9800';
    alertDiv.style.transition = 'all 0.5s ease';  // Add smooth transition
    alertDiv.style.opacity = '0';
    alertDiv.style.transform = 'translateX(100%)';
    
    document.body.appendChild(alertDiv);
    
    // Trigger fade in
    setTimeout(() => {
        alertDiv.style.opacity = '1';
        alertDiv.style.transform = 'translateX(0)';
    }, 10);

    // Remove the alert smoothly
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(100%)';
        
        // Remove the element after animation completes
        setTimeout(() => {
            alertDiv.remove();
            console.log('Alert removed');
        }, 500);
    }, 3000);
}

function testAlert() {
    showAlert('This is a test alert', 'success');
}

