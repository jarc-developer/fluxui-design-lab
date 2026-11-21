function syncHeadThemeAssets() {
    const styles = document.head.querySelectorAll('style#lab-theme');
    if (styles.length > 1) {
        for (let i = 0; i < styles.length - 1; i++) {
            styles[i].remove();
        }
    }

    const expectedFont = document.head.querySelector('meta[name="lab-theme-font"]')?.getAttribute('content') || '';
    const fontLinks = document.head.querySelectorAll('link[data-lab-theme-font]');
    let kept = false;

    for (let i = fontLinks.length - 1; i >= 0; i--) {
        const link = fontLinks[i];
        if (expectedFont !== '' && link.getAttribute('href') === expectedFont && !kept) {
            kept = true;
        } else {
            link.remove();
        }
    }
}

document.addEventListener('livewire:navigated', syncHeadThemeAssets);
