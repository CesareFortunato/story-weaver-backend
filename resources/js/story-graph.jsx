import React, { useCallback, useEffect, useMemo, useState } from "react";
import { createRoot } from "react-dom/client";

import {
    ReactFlow,
    Background,
    Controls,
    MiniMap,
    Handle,
    Position,
    applyNodeChanges,
    applyEdgeChanges,
} from "@xyflow/react";

import "@xyflow/react/dist/style.css";

const rootElement = document.getElementById("story-graph-root");

function StoryNode({ data }) {
    const node = data.node;
    const choicesCount = node.choices?.length ?? 0;
    const isFinalNode = choicesCount === 0;

    return (
        <div
            style={{
                minWidth: "220px",
                maxWidth: "260px",
                borderRadius: "14px",
                overflow: "hidden",
                border: node.is_start
                    ? "2px solid #facc15"
                    : isFinalNode
                        ? "2px solid #ef4444"
                        : "1px solid #334155",
                background: "#020617",
                color: "#e5e7eb",
                boxShadow: "0 18px 40px rgba(0,0,0,0.35)",
            }}
        >
            <Handle type="target" position={Position.Top} />

            <div
                style={{
                    padding: "10px 12px",
                    background: node.is_start
                        ? "#713f12"
                        : isFinalNode
                            ? "#7f1d1d"
                            : "#1e293b",
                    fontWeight: "700",
                    fontSize: "14px",
                }}
            >
                {node.is_start ? "⭐ " : ""}
                {node.title || "Nodo senza titolo"}
            </div>

            <div style={{ padding: "12px" }}>
                <p
                    style={{
                        margin: 0,
                        fontSize: "12px",
                        lineHeight: "1.4",
                        color: "#cbd5e1",
                    }}
                >
                    {node.text?.length > 90
                        ? node.text.substring(0, 90) + "..."
                        : node.text}
                </p>

                <div
                    style={{
                        display: "flex",
                        gap: "6px",
                        marginTop: "10px",
                        flexWrap: "wrap",
                    }}
                >
                    <span
                        style={{
                            fontSize: "11px",
                            padding: "3px 8px",
                            borderRadius: "999px",
                            background: "#0f172a",
                            color: "#93c5fd",
                        }}
                    >
                        ID {node.id}
                    </span>

                    <span
                        style={{
                            fontSize: "11px",
                            padding: "3px 8px",
                            borderRadius: "999px",
                            background: "#0f172a",
                            color: isFinalNode ? "#fca5a5" : "#86efac",
                        }}
                    >
                        {isFinalNode ? "Finale" : `${choicesCount} scelte`}
                    </span>
                </div>
            </div>

            <Handle type="source" position={Position.Bottom} />
        </div>
    );
}

function StoryGraph() {
    const graphUrl = rootElement.dataset.graphUrl;
    const positionUrlTemplate = rootElement.dataset.positionUrlTemplate;
    const nodeUrlTemplate = rootElement.dataset.nodeUrlTemplate;

    const [nodes, setNodes] = useState([]);
    const [edges, setEdges] = useState([]);
    const [selectedNode, setSelectedNode] = useState(null);

    const nodeTypes = useMemo(() => ({
        storyNode: StoryNode,
    }), []);

    useEffect(() => {
        fetchGraph();
    }, []);

    async function fetchGraph() {
        try {
            const response = await fetch(graphUrl);
            const json = await response.json();

            const styledNodes = json.data.nodes.map((node) => ({
                ...node,
                type: "storyNode",
            }));

            setNodes(styledNodes);
            setEdges(json.data.edges);
        } catch (error) {
            console.error("Errore caricamento grafo:", error);
        }
    }

    const onNodesChange = useCallback((changes) => {
        setNodes((currentNodes) =>
            applyNodeChanges(changes, currentNodes)
        );
    }, []);

    const onEdgesChange = useCallback((changes) => {
        setEdges((currentEdges) =>
            applyEdgeChanges(changes, currentEdges)
        );
    }, []);

    function onNodeClick(event, node) {
        setSelectedNode(node.data.node);
    }

    function onNodeDoubleClick(event, node) {
        const url = nodeUrlTemplate.replace("__NODE_ID__", node.id);

        window.location.href = url;
    }

    async function onNodeDragStop(event, node) {
        const url = positionUrlTemplate.replace("__NODE_ID__", node.id);

        try {
            await fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    position_x: Math.round(node.position.x),
                    position_y: Math.round(node.position.y),
                }),
            });
        } catch (error) {
            console.error("Errore salvataggio posizione nodo:", error);
        }
    }

    return (
        <div style={{ width: "100%", height: "100%", position: "relative" }}>
            <ReactFlow
                nodes={nodes}
                edges={edges}
                nodeTypes={nodeTypes}
                onNodesChange={onNodesChange}
                onEdgesChange={onEdgesChange}
                onNodeDragStop={onNodeDragStop}
                onNodeDoubleClick={onNodeDoubleClick}
                onNodeClick={onNodeClick}
                fitView
            >
                <Background />
                <Controls />
                <MiniMap />
            </ReactFlow>

            {selectedNode && (
                <div
                    style={{
                        position: "absolute",
                        top: 20,
                        right: 20,
                        width: "340px",
                        maxHeight: "85vh",
                        overflowY: "auto",
                        background: "#020617",
                        border: "1px solid #334155",
                        borderRadius: "16px",
                        padding: "18px",
                        color: "#e2e8f0",
                        zIndex: 50,
                        boxShadow: "0 20px 50px rgba(0,0,0,0.45)",
                    }}
                >
                    <div
                        style={{
                            display: "flex",
                            justifyContent: "space-between",
                            alignItems: "center",
                            marginBottom: "12px",
                        }}
                    >
                        <h2
                            style={{
                                margin: 0,
                                fontSize: "18px",
                                fontWeight: "700",
                            }}
                        >
                            {selectedNode.title || "Nodo senza titolo"}
                        </h2>

                        <button
                            onClick={() => setSelectedNode(null)}
                            style={{
                                background: "transparent",
                                border: "none",
                                color: "#94a3b8",
                                cursor: "pointer",
                                fontSize: "18px",
                            }}
                        >
                            ✕
                        </button>
                    </div>

                    {selectedNode.image && (
                        <img
                            src={`/storage/${selectedNode.image}`}
                            alt={selectedNode.title || "Immagine nodo"}
                            style={{
                                width: "100%",
                                borderRadius: "12px",
                                marginBottom: "14px",
                            }}
                        />
                    )}

                    <p
                        style={{
                            fontSize: "14px",
                            lineHeight: "1.6",
                            color: "#cbd5e1",
                        }}
                    >
                        {selectedNode.text}
                    </p>

                    <div
                        style={{
                            marginTop: "16px",
                            paddingTop: "14px",
                            borderTop: "1px solid #1e293b",
                        }}
                    >
                        <strong>Scelte:</strong>

                        {selectedNode.choices?.length > 0 ? (
                            <ul
                                style={{
                                    marginTop: "10px",
                                    paddingLeft: "18px",
                                }}
                            >
                                {selectedNode.choices.map((choice) => (
                                    <li
                                        key={choice.id}
                                        style={{
                                            marginBottom: "8px",
                                            color: "#93c5fd",
                                        }}
                                    >
                                        {choice.text}
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p
                                style={{
                                    color: "#fca5a5",
                                    marginTop: "10px",
                                }}
                            >
                                Nodo finale
                            </p>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}

if (rootElement) {
    createRoot(rootElement).render(<StoryGraph />);
}