import { createTheme } from '@mui/material/styles';

const fontFamily = "'Aptos', 'Segoe UI', ui-sans-serif, system-ui, sans-serif";

export const muiTheme = createTheme({
    palette: {
        mode: 'dark',
        primary: {
            main: '#06b6d4',
            light: '#67e8f9',
            dark: '#0891b2',
            contrastText: '#08111f',
        },
        secondary: {
            main: '#14b8a6',
            light: '#5eead4',
            dark: '#0f766e',
        },
        background: {
            default: '#08111f',
            paper: '#0f172a',
        },
        text: {
            primary: '#e5edf5',
            secondary: '#94a3b8',
        },
    },
    shape: {
        borderRadius: 8,
    },
    typography: {
        fontFamily,
        button: {
            fontWeight: 700,
            textTransform: 'none',
        },
    },
    components: {
        MuiButton: {
            defaultProps: {
                disableElevation: true,
            },
            styleOverrides: {
                root: {
                    borderRadius: 8,
                },
            },
        },
        MuiPaper: {
            styleOverrides: {
                root: {
                    backgroundImage: 'none',
                },
            },
        },
    },
});
