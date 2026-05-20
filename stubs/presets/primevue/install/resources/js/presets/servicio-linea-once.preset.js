import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';

const ServicioLineaOncePreset = definePreset(Aura, {
    primitive: {
        cyan: {
            50: '#ecfeff',
            100: '#cffafe',
            200: '#a5f3fc',
            300: '#67e8f9',
            400: '#22d3ee',
            500: '#06b6d4',
            600: '#0891b2',
            700: '#0e7490',
            800: '#155e75',
            900: '#164e63',
            950: '#083344'
        },
        electric: {
            50: '#f0f9ff',
            100: '#e0f2fe',
            200: '#bae6fd',
            300: '#7dd3fc',
            400: '#38bdf8',
            500: '#00e5ff',
            600: '#00b8d4',
            700: '#0284c7',
            800: '#0369a1',
            900: '#075985',
            950: '#082f49'
        },
        violet: {
            50: '#f5f3ff',
            100: '#ede9fe',
            200: '#ddd6fe',
            300: '#c4b5fd',
            400: '#a78bfa',
            500: '#8b5cf6',
            600: '#7c3aed',
            700: '#6d28d9',
            800: '#5b21b6',
            900: '#4c1d95',
            950: '#2e1065'
        }
    },

    semantic: {
        primary: {
            50: '{electric.50}',
            100: '{electric.100}',
            200: '{electric.200}',
            300: '{electric.300}',
            400: '{electric.400}',
            500: '{electric.500}',
            600: '{electric.600}',
            700: '{electric.700}',
            800: '{electric.800}',
            900: '{electric.900}',
            950: '{electric.950}'
        },

        focusRing: {
            width: '2px',
            style: 'solid',
            color: '{electric.400}',
            offset: '2px',
            shadow: '0 0 0 4px rgba(0, 229, 255, 0.18)'
        },

        borderRadius: {
            none: '0',
            xs: '4px',
            sm: '6px',
            md: '10px',
            lg: '14px',
            xl: '18px'
        },

        colorScheme: {
            light: {
                surface: {
                    0: '#ffffff',
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617'
                },

                primary: {
                    color: '{electric.600}',
                    contrastColor: '#ffffff',
                    hoverColor: '{electric.700}',
                    activeColor: '{electric.800}'
                },

                highlight: {
                    background: 'rgba(0, 229, 255, 0.14)',
                    focusBackground: 'rgba(124, 58, 237, 0.14)',
                    color: '{electric.700}',
                    focusColor: '{violet.700}'
                },

                formField: {
                    background: '#ffffff',
                    disabledBackground: '#f1f5f9',
                    filledBackground: '#f8fafc',
                    filledHoverBackground: '#f1f5f9',
                    filledFocusBackground: '#ffffff',
                    borderColor: '#cbd5e1',
                    hoverBorderColor: '{electric.400}',
                    focusBorderColor: '{electric.500}',
                    invalidBorderColor: '#ef4444',
                    color: '#0f172a',
                    disabledColor: '#94a3b8',
                    placeholderColor: '#64748b',
                    invalidPlaceholderColor: '#ef4444',
                    floatLabelColor: '#64748b',
                    floatLabelFocusColor: '{electric.600}',
                    floatLabelActiveColor: '#475569',
                    floatLabelInvalidColor: '#ef4444',
                    iconColor: '#64748b',
                    shadow: '0 1px 2px rgba(15, 23, 42, 0.06)'
                },

                text: {
                    color: '#0f172a',
                    hoverColor: '#020617',
                    mutedColor: '#64748b',
                    hoverMutedColor: '#475569'
                },

                content: {
                    background: '#ffffff',
                    hoverBackground: '#f8fafc',
                    borderColor: '#e2e8f0',
                    color: '#0f172a',
                    hoverColor: '#020617'
                }
            },

            dark: {
                surface: {
                    0: '#ffffff',
                    50: '#e2e8f0',
                    100: '#cbd5e1',
                    200: '#94a3b8',
                    300: '#64748b',
                    400: '#475569',
                    500: '#334155',
                    600: '#1e293b',
                    700: '#172033',
                    800: '#0f172a',
                    900: '#080f1f',
                    950: '#020617'
                },

                primary: {
                    color: '{electric.400}',
                    contrastColor: '#020617',
                    hoverColor: '{electric.300}',
                    activeColor: '{electric.200}'
                },

                highlight: {
                    background: 'rgba(0, 229, 255, 0.16)',
                    focusBackground: 'rgba(139, 92, 246, 0.18)',
                    color: '{electric.200}',
                    focusColor: '{violet.200}'
                },

                formField: {
                    background: 'rgba(15, 23, 42, 0.82)',
                    disabledBackground: 'rgba(30, 41, 59, 0.72)',
                    filledBackground: 'rgba(15, 23, 42, 0.92)',
                    filledHoverBackground: 'rgba(30, 41, 59, 0.9)',
                    filledFocusBackground: 'rgba(2, 6, 23, 0.95)',
                    borderColor: 'rgba(148, 163, 184, 0.24)',
                    hoverBorderColor: 'rgba(0, 229, 255, 0.65)',
                    focusBorderColor: '{electric.400}',
                    invalidBorderColor: '#fb7185',
                    color: '#e2e8f0',
                    disabledColor: '#64748b',
                    placeholderColor: '#94a3b8',
                    invalidPlaceholderColor: '#fb7185',
                    floatLabelColor: '#94a3b8',
                    floatLabelFocusColor: '{electric.300}',
                    floatLabelActiveColor: '#cbd5e1',
                    floatLabelInvalidColor: '#fb7185',
                    iconColor: '#94a3b8',
                    shadow: '0 0 0 1px rgba(255,255,255,0.02), 0 10px 30px rgba(0,0,0,0.28)'
                },

                text: {
                    color: '#e2e8f0',
                    hoverColor: '#ffffff',
                    mutedColor: '#94a3b8',
                    hoverMutedColor: '#cbd5e1'
                },

                content: {
                    background: 'rgba(15, 23, 42, 0.86)',
                    hoverBackground: 'rgba(30, 41, 59, 0.86)',
                    borderColor: 'rgba(148, 163, 184, 0.18)',
                    color: '#e2e8f0',
                    hoverColor: '#ffffff'
                }
            }
        }
    },

    components: {
        button: {
            borderRadius: '8px',
            paddingX: '1rem',
            paddingY: '0.7rem',
            gap: '0.5rem',
            fontWeight: '700',

            primary: {
                background: 'linear-gradient(135deg, #12c8ff 0%, #8b5cf6 100%)',
                hoverBackground: 'linear-gradient(135deg, #67e8f9 0%, #a78bfa 100%)',
                activeBackground: 'linear-gradient(135deg, #06b6d4 0%, #7c3aed 100%)',
                borderColor: 'transparent',
                hoverBorderColor: 'transparent',
                activeBorderColor: 'transparent',
                color: '#ffffff',
                hoverColor: '#ffffff',
                activeColor: '#ffffff',
                shadow: '0 14px 34px rgba(0, 229, 255, 0.26)'
            },

            secondary: {
                background: 'rgba(148, 163, 184, 0.10)',
                hoverBackground: 'rgba(148, 163, 184, 0.16)',
                activeBackground: 'rgba(148, 163, 184, 0.22)',
                borderColor: 'rgba(148, 163, 184, 0.22)',
                hoverBorderColor: 'rgba(0, 229, 255, 0.38)'
            }
        },

        card: {
            borderRadius: '8px',
            shadow: '0 24px 70px rgba(2, 6, 23, 0.16)'
        },

        dialog: {
            borderRadius: '8px',
            shadow: '0 30px 90px rgba(0, 0, 0, 0.55)'
        },

        panel: {
            borderRadius: '8px'
        },

        datatable: {
            headerCell: {
                background: 'rgba(15, 23, 42, 0.04)',
                color: '{text.color}',
                fontWeight: '800'
            },
            row: {
                hoverBackground: 'rgba(0, 229, 255, 0.06)'
            }
        },

        inputtext: {
            borderRadius: '8px',
            paddingX: '0.9rem',
            paddingY: '0.72rem'
        },

        select: {
            borderRadius: '8px'
        },

        textarea: {
            borderRadius: '8px'
        },

        tabs: {
            tab: {
                borderRadius: '8px',
                fontWeight: '700'
            },
            activeBar: {
                height: '3px',
                background: 'linear-gradient(90deg, #00e5ff, #8b5cf6)'
            }
        },

        tag: {
            borderRadius: '999px',
            fontWeight: '700'
        },

        toast: {
            borderRadius: '18px',
            shadow: '0 20px 60px rgba(2, 6, 23, 0.28)'
        }
    }
});

export default ServicioLineaOncePreset;
